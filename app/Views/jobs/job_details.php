<nav class="navbar bg-body-tertiary custom-background">
    <div class="container-fluid">
      <a href="<?= base_url('jobs-board') ?>" class="navbar-brand banner-title px-3">Grad Dev Jobs</a>

    </div>
  </nav>
<div class="container mt-4">


    <div class="d-flex justify-content-end mb-3">
    <a href="<?= base_url('jobs-board') ?>" class="btn custom-button">Back to Jobs Board</a>
    </div>

    <h1><?= esc($job['job_title'])?></h1>
    <h2 class="text-muted"><?=esc($job['employer_name'])?></h2>
    <hr>
    
    <p><strong>Location:</strong> <?= esc($job['location']) ?></p>
    <p><strong>Salary:</strong>
        <?php
        if ($job['minimum_salary']) {
            echo '£' . number_format($job['minimum_salary']);
            if ($job['maximum_salary']) {
                echo ' - £' . number_format($job['maximum_salary']);
            }
        } else {
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
    <!-- Link to job application on Reed -->

    <a href="<?= esc($job['job_url']) ?>" target= "_blank" class="btn custom-button">Apply on Reed</a>
</div>