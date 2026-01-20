import "./bootstrap";
const $ = window.jQuery;

// Import Module Components
import { initAuth } from './components/auth';
import { initValidation } from './components/validation';
import { initSignature } from './components/signature';
import { initDatatable } from './components/datatable';
import { initUI } from './components/ui';

$(document).ready(function() {
    initAuth();        // Jalan di halaman login
    initUI();          // Jalan di semua halaman (sidebar)
    initValidation();  // Jalan di form BAP
    initSignature();   // Jalan di form BAP (modal)
    initDatatable();   // Jalan di index BAP
});