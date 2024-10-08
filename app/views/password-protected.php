<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo get_bloginfo('title') ?></title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>

  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-sm-12 col-md-8 col-lg-6">
        <h3>Acesso permitido via senha</h3>

        <?php if (isset($_GET['loginError'])) : ?>
          <div class="alert alert-danger" role="alert">
            Senha incorreta, tente novamente.
          </div>
        <?php endif; ?>

        <form method="post">
          <?php wp_nonce_field(); ?>
          <div class="mb-2">
            <label class="form-label" for="password">Senha de acesso</label>
            <input type="password" name="password" id="password" class="form-control" required>
          </div>

          <button class="btn btn-primary">Ver site</button>
        </form>
      </div>
    </div>

  </div>
</body>

</html>