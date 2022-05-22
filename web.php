<div class="row">
  <div class="col-8 ml-4">
    <ol>
      <?php if ($web->partialmatchingImages() != null) : ?>
        <?php foreach ($web->partialmatchingImages() as $key => $partialImages) : ?>
          <li class="font-weight-bold">
            <h6>
              <a href="<?php echo ucfirst($partialImages->info()['url']); ?>"><?php echo ucfirst($partialImages->info()['url']); ?></a>
            </h6>
          </li>
        <?php endforeach ?>
      <?php elseif ($web->matchingImages() != null) : ?>
        <?php foreach ($web->matchingImages() as $key => $partialImages) : ?>
          <li class="font-weight-bold">
            <h6>
              <a href="<?php echo ucfirst($partialImages->info()['url']); ?>"><?php echo ucfirst($partialImages->info()['url']); ?></a>
            </h6>
          </li>
        <?php endforeach ?>
      <?php else : ?>
        <li class="font-weight-bold">
          <h6>
            <a href="# ?>"><?php echo "No Matching Images" ?></a>
          </h6>
        </li>
      <?php endif ?>
    </ol>
  </div>
</div>