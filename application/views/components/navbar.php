<style>
    .navbar {
        background-color: var(--tasklog-surface);
        border-color: var(--tasklog-border) !important;
    }

    .navbar-brand {
        color: var(--tasklog-text);
    }

    .navbar-brand:hover {
        color: var(--tasklog-text);
    }
</style>

<nav class="navbar navbar-dark border-bottom border-tasklog">

    <div class="container py-2">

        <a class="navbar-brand fw-bold fs-3" href="<?= site_url('dashboard'); ?>">
            Task<span class="text-cyan">Log</span>
        </a>

        <div class="d-flex align-items-center gap-3">

            <span class="text-secondary d-none d-md-block">
                <?= html_escape($this->session->userdata('name')); ?>
            </span>

            <a href="<?= site_url('auth/logout'); ?>" class="btn btn-info fw-semibold">
                Logout
            </a>

        </div>

    </div>

</nav>