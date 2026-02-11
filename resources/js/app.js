import "./bootstrap";
const $ = window.jQuery;

// Import module components
import { initAuth } from './components/auth';
import { initDashboard } from './components/dashboard';
import { initValidation } from './components/validation';
import { initSignature } from './components/signature';
import { initDatatable } from './components/datatable';
import { initUI } from './components/ui';
import { initLoader } from './components/loader';
import { initUnsavedHandler } from './components/unsavedHandler';

$(document).ready(function() {
    initAuth();           // Jalan di halaman login
    initDashboard();      // Jalan di halaman dashboard
    initUI();             // Jalan di semua halaman (sidebar)
    initValidation();     // Jalan di form BAP
    initSignature();      // Jalan di form BAP (modal)
    initDatatable();      // Jalan di index BAP
    initLoader();         // Jalan di semua halaman (global)
    initUnsavedHandler(); // Jalan di semua halaman (form)
});