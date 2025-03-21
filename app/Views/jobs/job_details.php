<nav class="navbar bg-body-tertiary custom-background">
    <div class="container-fluid">
      <a href="<?= base_url('jobs-board') ?>" class="navbar-brand banner-title px-3">Grad Dev Jobs</a>
    </div>
</nav>

<div class="container mt-4">

    <!-- Buttons -->
    <div class="d-flex justify-content-between mb-3">
        <!-- Back to Jobs Board button -->
        <a href="<?= base_url('jobs-board') ?>" class="btn custom-button">Back</a>

        <!-- Apply on Reed button -->
        <a href="<?= esc($job['job_url']) ?>" target="_blank" class="btn custom-button">Apply on Reed</a>
    </div>

    <h1><?= esc($job['job_title'])?></h1>
    <h2 class="text-muted"><?= esc($job['employer_name']) ?></h2>
    <hr>
    
    <p><strong>Location:</strong> <?= esc($job['location']) ?></p>
    <p><strong>Salary:</strong>
        <?php
        // If there's salary info, format and display it
        if ($job['minimum_salary']) {
            echo '£' . number_format($job['minimum_salary']);
            if ($job['maximum_salary']) {
                echo ' - £' . number_format($job['maximum_salary']);
            }
        } else {
            // If no salary info, show default message
            echo 'Not specified';
        }
        ?>
    </p>

    <p><strong>Applications:</strong> <?= esc($job['applications_count']) ?? '0' ?></p>
    <p><strong>Application Deadline:</strong> <?= esc($job['expiration_date']) ?></p>
    <p><strong>Job Posted:</strong> <?= esc($job['reed_creation_date']) ?></p>

    <hr>
    <!-- Full job description, keep actual formatting returned from reed-->
    <?= htmlspecialchars_decode($job['job_description']) ?>
    <hr>

</div>
