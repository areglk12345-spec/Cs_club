<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>ประเมินกิจกรรม
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0 text-center"><i class="fas fa-star"></i> ประเมินความพึงพอใจกิจกรรม</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h4 class="text-primary">
                            <?= $activity['activity_name'] ?>
                        </h4>
                        <p class="text-muted"><i class="fas fa-map-marker-alt"></i>
                            <?= $activity['location'] ?>
                        </p>
                    </div>

                    <form action="<?= base_url('student/feedback/save') ?>" method="post">
                        <input type="hidden" name="activity_id" value="<?= $activity['activity_id'] ?>">

                        <div class="mb-4 text-center">
                            <label class="form-label d-block fw-bold mb-3">ระดับความพึงพอใจ</label>
                            <div class="star-rating h2">
                                <?php $r = $existing_feedback ? $existing_feedback['rating'] : 5; ?>
                                <div class="form-check form-check-inline">
                                    <input class="btn-check" type="radio" name="rating" id="r1" value="1"
                                        <?= $r == 1 ? 'checked' : '' ?>>
                                    <label class="btn btn-outline-danger" for="r1">1</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="btn-check" type="radio" name="rating" id="r2" value="2"
                                        <?= $r == 2 ? 'checked' : '' ?>>
                                    <label class="btn btn-outline-warning" for="r2">2</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="btn-check" type="radio" name="rating" id="r3" value="3"
                                        <?= $r == 3 ? 'checked' : '' ?>>
                                    <label class="btn btn-outline-info" for="r3">3</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="btn-check" type="radio" name="rating" id="r4" value="4"
                                        <?= $r == 4 ? 'checked' : '' ?>>
                                    <label class="btn btn-outline-primary" for="r4">4</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="btn-check" type="radio" name="rating" id="r5" value="5"
                                        <?= $r == 5 ? 'checked' : '' ?>>
                                    <label class="btn btn-outline-success" for="r5">5</label>
                                </div>
                            </div>
                            <div class="mt-2 small text-muted">
                                (1 = น้อยที่สุด, 5 = มากที่สุด)
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">ข้อเสนอแนะเพิ่มเติม</label>
                            <textarea name="comment" class="form-control" rows="4"
                                placeholder="แสดงความคิดเห็นของคุณที่นี่..."><?= $existing_feedback ? $existing_feedback['comment'] : '' ?></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning btn-lg fw-bold">
                                <i class="fas fa-paper-plane"></i> ส่งข้อมูลประเมิน
                            </button>
                            <a href="<?= base_url('student/history') ?>" class="btn btn-link text-muted">ยกเลิก</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-check:checked+.btn {
        transform: scale(1.1);
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        font-weight: bold;
    }
</style>

<?= $this->endSection() ?>