<div class="row">
  <div class="col-12">
    <ol>
      <?php foreach ($labels as $key => $label) : ?>
        <li class="font-weight-bold">
          <h6 class="font-weight-bold"><?php echo ucfirst($label->info()['description']); ?></h6>
        </li>
        <span class="text-dark">Confidence: strong <?php echo number_format($label->info()['score'] * 100, 2); ?></span>
      <?php endforeach ?>
    </ol>
  </div>
</div>