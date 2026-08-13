<style>
/* ─── Dashboard Layout ──────────────────────────────── */
.dash-wrapper {
    display: flex;
    min-height: calc(100vh - 96px);
    background: #f5f6fa;
}

.dash-sidebar {
    width: 230px;
    flex-shrink: 0;
    background: #fff;
    border-right: 1px solid #e8eaf0;
    display: flex;
    flex-direction: column;
    padding: 1.25rem 0;
    position: sticky;
    top: 96px;
    height: calc(100vh - 96px);
    overflow-y: auto;
}

.dash-sidebar__brand {
    padding: 0 1.1rem 1rem;
    border-bottom: 1px solid #f0f1f5;
    margin-bottom: .75rem;
}

.dash-brand-link {
    display: flex;
    align-items: center;
    text-decoration: none;
    font-family: 'Playfair Display', serif;
    font-size: 1rem;
    font-weight: 600;
    color: #1a1a1a;
}
.dash-brand-link:hover { color: #1a6644; }

.dash-nav { padding: 0 .75rem; }
.dash-nav__label {
    font-size: .65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .09em;
    color: #9ca3af;
    margin: .5rem 0 .25rem .4rem;
}
.dash-nav__link {
    display: flex;
    align-items: center;
    gap: .6rem;
    font-size: .855rem;
    font-weight: 500;
    color: #374151;
    text-decoration: none;
    padding: .5rem .75rem;
    border-radius: .45rem;
    transition: background .15s, color .15s;
    cursor: pointer;
}
.dash-nav__link:hover { background: #f0f9f4; color: #1a6644; }
.dash-nav__link.active { background: #e6f4ed; color: #1a6644; font-weight: 600; }
.dash-nav__link--danger { color: #c53030; }
.dash-nav__link--danger:hover { background: #fff5f5 !important; color: #9b2c2c !important; }

.dash-main { flex: 1; padding: 1.75rem 2rem; min-width: 0; }

/* Alerts */
.dash-alert {
    display: flex;
    align-items: center;
    padding: .75rem 1rem;
    border-radius: .5rem;
    font-size: .875rem;
    margin-bottom: 1.25rem;
    font-weight: 500;
}
.dash-alert--success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
.dash-alert--danger  { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

/* Page header */
.dash-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: .75rem;
}
.dash-page-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.4rem;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0;
}

/* Buttons */
.btn-dash-primary {
    display: inline-flex; align-items: center; gap: .4rem;
    background: #1a6644; color: #fff; border: none;
    padding: .5rem 1.1rem; border-radius: .45rem;
    font-size: .84rem; font-weight: 600; text-decoration: none;
    cursor: pointer; transition: background .18s;
}
.btn-dash-primary:hover { background: #155537; color: #fff; }

.btn-dash-secondary {
    display: inline-flex; align-items: center; gap: .4rem;
    background: #fff; color: #374151; border: 1.5px solid #d1d5db;
    padding: .48rem 1.1rem; border-radius: .45rem;
    font-size: .84rem; font-weight: 500; text-decoration: none;
    cursor: pointer; transition: border-color .18s, background .18s;
}
.btn-dash-secondary:hover { border-color: #1a6644; color: #1a6644; background: #f0f9f4; }

.btn-dash-danger {
    display: inline-flex; align-items: center; gap: .4rem;
    background: #fee2e2; color: #991b1b; border: 1.5px solid #fca5a5;
    padding: .48rem 1rem; border-radius: .45rem;
    font-size: .84rem; font-weight: 500;
    cursor: pointer; transition: background .18s;
}
.btn-dash-danger:hover { background: #fca5a5; color: #7f1d1d; }

/* Table */
.dash-table-wrap {
    background: #fff; border-radius: .6rem;
    border: 1px solid #e8eaf0; overflow: hidden;
}
.dash-table { width: 100%; border-collapse: collapse; font-size: .845rem; }
.dash-table thead th {
    background: #f8f9fb; color: #6b7280; font-weight: 600;
    font-size: .75rem; text-transform: uppercase; letter-spacing: .06em;
    padding: .75rem 1rem; border-bottom: 1px solid #e8eaf0; white-space: nowrap;
}
.dash-table tbody td {
    padding: .75rem 1rem; border-bottom: 1px solid #f3f4f6;
    color: #374151; vertical-align: middle;
}
.dash-table tbody tr:last-child td { border-bottom: none; }
.dash-table tbody tr:hover td { background: #fafbfc; }

/* Badges */
.badge-active   { background: #d1fae5; color: #065f46; padding: .2rem .6rem; border-radius: 2rem; font-size: .72rem; font-weight: 600; }
.badge-inactive { background: #fee2e2; color: #991b1b; padding: .2rem .6rem; border-radius: 2rem; font-size: .72rem; font-weight: 600; }

/* Card */
.dash-card {
    background: #fff; border: 1px solid #e8eaf0;
    border-radius: .65rem; padding: 1.75rem;
}

/* Form */
.dash-form-label {
    font-size: .8rem; font-weight: 600; color: #374151;
    margin-bottom: .3rem; display: block;
}
.dash-form-input,
.dash-form-textarea,
.dash-form-select {
    width: 100%; border: 1.5px solid #d1d5db; border-radius: .45rem;
    padding: .5rem .75rem; font-size: .855rem; color: #1a1a1a;
    background: #fff; transition: border-color .18s, box-shadow .18s;
    font-family: 'Inter', sans-serif;
}
.dash-form-input:focus,
.dash-form-textarea:focus,
.dash-form-select:focus {
    outline: none; border-color: #1a6644;
    box-shadow: 0 0 0 3px rgba(26,102,68,.1);
}
.dash-form-textarea { resize: vertical; min-height: 90px; }
.dash-form-error { font-size: .77rem; color: #c53030; margin-top: .25rem; }

/* Section headings */
.dash-form-section {
    font-size: .7rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .09em; color: #9ca3af;
    margin: 1.5rem 0 .75rem; padding-bottom: .4rem;
    border-bottom: 1px solid #f3f4f6;
}

/* Filter bar */
.dash-filter-bar {
    display: flex; gap: .75rem; flex-wrap: wrap;
    margin-bottom: 1.25rem; align-items: center;
}
.dash-filter-bar .dash-form-input { max-width: 280px; }

@media (max-width: 768px) {
    .dash-sidebar { display: none; }
    .dash-main { padding: 1rem; }
}
</style>
