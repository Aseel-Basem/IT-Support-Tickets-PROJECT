/* =============================================
   YIC IT Support Portal - Main JavaScript
   Phase 2: Frontend Development
   ============================================= */

'use strict';

// =============================================
// NAVBAR: Hamburger toggle for mobile
// =============================================
document.addEventListener('DOMContentLoaded', function () {
  const hamburger = document.querySelector('.hamburger');
  const navLinks  = document.querySelector('.nav-links');

  if (hamburger && navLinks) {
    hamburger.addEventListener('click', function () {
      navLinks.classList.toggle('open');
      const isOpen = navLinks.classList.contains('open');
      hamburger.setAttribute('aria-expanded', isOpen);
    });

    // Close menu when a link is clicked
    navLinks.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        navLinks.classList.remove('open');
      });
    });

    // Close menu when clicking outside
    document.addEventListener('click', function (e) {
      if (!hamburger.contains(e.target) && !navLinks.contains(e.target)) {
        navLinks.classList.remove('open');
      }
    });
  }
});

// =============================================
// FORM VALIDATION HELPERS
// =============================================

/**
 * Show an error under a field.
 */
function showError(fieldId, message) {
  const field = document.getElementById(fieldId);
  if (!field) return;
  field.classList.add('error');
  field.classList.remove('success');
  const errEl = document.getElementById(fieldId + '-error');
  if (errEl) { errEl.textContent = message; errEl.classList.add('visible'); }
}

/**
 * Clear error from a field.
 */
function clearError(fieldId) {
  const field = document.getElementById(fieldId);
  if (!field) return;
  field.classList.remove('error');
  field.classList.add('success');
  const errEl = document.getElementById(fieldId + '-error');
  if (errEl) { errEl.classList.remove('visible'); }
}

/**
 * Validate that a field is not empty.
 */
function validateRequired(fieldId, label) {
  const field = document.getElementById(fieldId);
  if (!field) return true;
  if (!field.value.trim()) {
    showError(fieldId, label + ' is required.');
    return false;
  }
  clearError(fieldId);
  return true;
}

/**
 * Validate email format.
 */
function validateEmail(fieldId) {
  const field = document.getElementById(fieldId);
  if (!field) return true;
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!field.value.trim()) { showError(fieldId, 'Email is required.'); return false; }
  if (!emailRegex.test(field.value.trim())) { showError(fieldId, 'Please enter a valid email address.'); return false; }
  clearError(fieldId);
  return true;
}

/**
 * Validate password strength (min 8 chars, at least 1 number).
 */
function validatePassword(fieldId) {
  const field = document.getElementById(fieldId);
  if (!field) return true;
  const val = field.value;
  if (!val) { showError(fieldId, 'Password is required.'); return false; }
  if (val.length < 8) { showError(fieldId, 'Password must be at least 8 characters.'); return false; }
  if (!/\d/.test(val)) { showError(fieldId, 'Password must contain at least one number.'); return false; }
  clearError(fieldId);
  return true;
}

/**
 * Validate that confirm password matches.
 */
function validateConfirmPassword(passId, confirmId) {
  const pass    = document.getElementById(passId);
  const confirm = document.getElementById(confirmId);
  if (!pass || !confirm) return true;
  if (!confirm.value) { showError(confirmId, 'Please confirm your password.'); return false; }
  if (pass.value !== confirm.value) { showError(confirmId, 'Passwords do not match.'); return false; }
  clearError(confirmId);
  return true;
}

/**
 * Validate minimum length.
 */
function validateMinLength(fieldId, label, minLen) {
  const field = document.getElementById(fieldId);
  if (!field) return true;
  if (!field.value.trim()) { showError(fieldId, label + ' is required.'); return false; }
  if (field.value.trim().length < minLen) {
    showError(fieldId, label + ' must be at least ' + minLen + ' characters.');
    return false;
  }
  clearError(fieldId);
  return true;
}

// =============================================
// PASSWORD STRENGTH METER
// =============================================
function initPasswordStrength(inputId) {
  const input = document.getElementById(inputId);
  const bar   = document.getElementById(inputId + '-strength-bar');
  const label = document.getElementById(inputId + '-strength-label');
  if (!input || !bar) return;

  input.addEventListener('input', function () {
    const val = input.value;
    let score = 0;
    if (val.length >= 8)   score++;
    if (/[A-Z]/.test(val)) score++;
    if (/\d/.test(val))    score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const widths = ['0%', '25%', '50%', '75%', '100%'];
    const colors = ['#e74c3c', '#e74c3c', '#f39c12', '#3498db', '#2ecc71'];
    const labels = ['', 'Weak', 'Fair', 'Good', 'Strong'];

    bar.style.width     = widths[score];
    bar.style.background = colors[score];
    if (label) label.textContent = labels[score];
  });
}

// =============================================
// REGISTER FORM VALIDATION
// =============================================
function initRegisterForm() {
  const form = document.getElementById('register-form');
  if (!form) return;

  initPasswordStrength('password');

  // Live validation
  ['full_name', 'email', 'student_id', 'password', 'confirm_password'].forEach(function (id) {
    const el = document.getElementById(id);
    if (el) el.addEventListener('blur', function () { validateField(id); });
  });

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    let valid = true;
    valid &= validateRequired('full_name', 'Full name');
    valid &= validateEmail('email');
    valid &= validateRequired('student_id', 'Student/Staff ID');
    valid &= validatePassword('password');
    valid &= validateConfirmPassword('password', 'confirm_password');

    if (valid) {
      const btn = form.querySelector('[type="submit"]');
      btn.disabled = true;
      btn.innerHTML = '<span class="spinner"></span> Creating account...';
      // Simulate submission delay
      setTimeout(function () {
        showAlert('Account created successfully! You can now log in.', 'success', form);
        form.reset();
        btn.disabled = false;
        btn.innerHTML = 'Create Account';
        document.querySelectorAll('.form-control').forEach(function (el) {
          el.classList.remove('success', 'error');
        });
      }, 1400);
    }
  });
}

function validateField(id) {
  if (id === 'email')            return validateEmail(id);
  if (id === 'password')         return validatePassword(id);
  if (id === 'confirm_password') return validateConfirmPassword('password', id);
  return validateRequired(id, id.replace(/_/g, ' '));
}

// =============================================
// LOGIN FORM VALIDATION
// =============================================
function initLoginForm() {
  const form = document.getElementById('login-form');
  if (!form) return;

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    let valid = true;
    valid &= validateEmail('email');
    valid &= validateRequired('password', 'Password');

    if (valid) {
      const btn = form.querySelector('[type="submit"]');
      btn.disabled = true;
      btn.innerHTML = '<span class="spinner"></span> Signing in...';
      setTimeout(function () {
        // Simulated: In real app, this submits to PHP
        window.location.href = 'my_tickets.html';
      }, 1200);
    }
  });
}

// =============================================
// SUBMIT TICKET FORM VALIDATION
// =============================================
function initTicketForm() {
  const form = document.getElementById('ticket-form');
  if (!form) return;

  // Live character counter for description
  const desc    = document.getElementById('description');
  const counter = document.getElementById('desc-counter');
  if (desc && counter) {
    desc.addEventListener('input', function () {
      counter.textContent = desc.value.length + ' / 1000';
      if (desc.value.length > 1000) counter.style.color = '#e74c3c';
      else counter.style.color = '';
    });
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    let valid = true;
    valid &= validateRequired('category', 'Category');
    valid &= validateMinLength('title', 'Title', 10);
    valid &= validateMinLength('description', 'Description', 30);

    // Check description length max
    if (desc && desc.value.length > 1000) {
      showError('description', 'Description cannot exceed 1000 characters.');
      valid = false;
    }

    if (valid) {
      const btn = form.querySelector('[type="submit"]');
      btn.disabled = true;
      btn.innerHTML = '<span class="spinner"></span> Submitting...';
      setTimeout(function () {
        showAlert('Your ticket has been submitted successfully! Ticket #' + Math.floor(1000 + Math.random() * 9000) + ' has been created.', 'success', form);
        form.reset();
        if (counter) counter.textContent = '0 / 1000';
        btn.disabled = false;
        btn.innerHTML = '&#128228; Submit Ticket';
        document.querySelectorAll('.form-control').forEach(function (el) {
          el.classList.remove('success', 'error');
        });
      }, 1400);
    }
  });
}

// =============================================
// ADMIN STATUS UPDATE (admin_dashboard.html)
// =============================================
function initAdminDashboard() {
  const form = document.getElementById('update-status-form');
  if (!form) return;

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    const ticketId = document.getElementById('ticket_id');
    const newStatus = document.getElementById('new_status');
    if (!ticketId || !newStatus) return;

    if (!ticketId.value.trim()) { showError('ticket_id', 'Ticket ID is required.'); return; }
    if (!newStatus.value) { showError('new_status', 'Please select a status.'); return; }

    clearError('ticket_id');
    clearError('new_status');

    const btn = form.querySelector('[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner"></span> Updating...';

    setTimeout(function () {
      showAlert('Ticket #' + ticketId.value + ' status updated to "' + newStatus.options[newStatus.selectedIndex].text + '".', 'success', form.closest('.card'));
      form.reset();
      btn.disabled = false;
      btn.innerHTML = 'Update Status';
    }, 900);
  });

  // Filter tickets by status
  const filterSelect = document.getElementById('filter-status');
  if (filterSelect) {
    filterSelect.addEventListener('change', function () {
      const val = filterSelect.value;
      document.querySelectorAll('[data-status]').forEach(function (row) {
        if (!val || row.dataset.status === val) row.style.display = '';
        else row.style.display = 'none';
      });
    });
  }
}

// =============================================
// SEARCH (my_tickets.html)
// =============================================
function initTicketSearch() {
  const searchInput = document.getElementById('ticket-search');
  if (!searchInput) return;

  searchInput.addEventListener('input', function () {
    const query = searchInput.value.toLowerCase();
    document.querySelectorAll('.ticket-card').forEach(function (card) {
      const text = card.textContent.toLowerCase();
      card.style.display = text.includes(query) ? '' : 'none';
    });

    const empty = document.getElementById('no-results');
    const visible = document.querySelectorAll('.ticket-card:not([style*="none"])');
    if (empty) empty.classList.toggle('hidden', visible.length > 0);
  });
}

// =============================================
// SHOW ALERT utility
// =============================================
function showAlert(message, type, parentEl) {
  const icons = { success: '✔', danger: '✖', warning: '⚠', info: 'ℹ' };
  const alert = document.createElement('div');
  alert.className = 'alert alert-' + type;
  alert.innerHTML = '<span>' + (icons[type] || 'ℹ') + '</span> ' + message;

  // Remove previous alerts
  if (parentEl) {
    const prev = parentEl.querySelector('.alert');
    if (prev) prev.remove();
    parentEl.insertBefore(alert, parentEl.firstChild);
  } else {
    document.querySelector('main').prepend(alert);
  }

  // Auto-dismiss after 6s
  setTimeout(function () { alert.remove(); }, 6000);
}

// =============================================
// INIT on page load
// =============================================
document.addEventListener('DOMContentLoaded', function () {
  initRegisterForm();
  initLoginForm();
  initTicketForm();
  initAdminDashboard();
  initTicketSearch();
});
