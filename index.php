<?php
require 'config.php';
session_start();

// Verifica login
if (!isset($_SESSION['logado'])) {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha = $_POST['senha'] ?? '';
    if ($senha === 'SUA SENHA DE ACESSO') {
      $_SESSION['logado'] = true;
      header('Location: index.php');
      exit;
    } else {
      $erro = 'Senha incorreta!';
    }
  }
  ?>
  <!DOCTYPE html>
  <html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <title>Login - Campanhas</title>
	<style>
	  body {
		font-family: Arial, sans-serif;
		background: #f5f7fa;
		display: flex;
		justify-content: center;
		align-items: center;
		height: 100vh;
		margin: 0;
	  }

	  .login-box {
		background: #fff;
		padding: 2rem;
		border-radius: 8px;
		box-shadow: 0 4px 12px rgba(0,0,0,0.1);
		width: 320px;
		text-align: center;
	  }

	  input[type=password] {
		width: 90%;
		padding: 0.8rem;
		margin: 0.8rem 0;
		border: 1px solid #ccc;
		border-radius: 4px;
		font-size: 1rem;
	  }

	  button {
		background: #007bff;
		color: white;
		border: none;
		padding: 0.8rem 1.2rem;
		font-size: 1rem;
		border-radius: 4px;
		cursor: pointer;
		width: 100%;
	  }

	  button:hover {
		background: #0056b3;
	  }

	  .error {
		color: #d9534f;
		margin-top: 0.5rem;
	  }

	  .details-table {
		width: 100%;
		border-collapse: collapse;
		margin: 10px 0;
	  }

	  .details-table th,
	  .details-table td {
		border: 1px solid #ccc;
		padding: 8px;
		text-align: left;
	  }

	  .details-table th {
		background-color: #f2f2f2;
	  }

	  .details {
		margin-bottom: 20px;
	  }
	</style>
  </head>
  <body>
    <div class="login-box">
      <h2>Login</h2>
      <?php if (!empty($erro)): ?>
        <div class="error"><?= htmlspecialchars($erro) ?></div>
      <?php endif; ?>
      <form method="post">
        <input type="password" name="senha" placeholder="Senha" required autofocus />
        <button type="submit">Entrar</button>
      </form>
    </div>
  </body>
  </html>
  <?php
  exit;
}

// Excluir campanha se solicitado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_campanha'])) {
    $campanha_excluir = $_POST['excluir_campanha'];

    $stmt1 = $pdo->prepare("DELETE FROM aberturas WHERE campanha = :campanha");
    $stmt2 = $pdo->prepare("DELETE FROM cliques WHERE campanha = :campanha");
    $stmt3 = $pdo->prepare("DELETE FROM conversoes WHERE campanha = :campanha");

    $pdo->beginTransaction();
    try {
        $stmt1->execute(['campanha' => $campanha_excluir]);
        $stmt2->execute(['campanha' => $campanha_excluir]);
        $stmt3->execute(['campanha' => $campanha_excluir]);
        $pdo->commit();

        $_SESSION['mensagem_sucesso'] = "Campanha '{$campanha_excluir}' excluída com sucesso!";
        header('Location: index.php');
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['erro_exclusao'] = "Erro ao excluir a campanha: " . $e->getMessage();
        header('Location: index.php');
        exit;
    }
}

// Se estiver filtrando por campanha
$campanha_atual = $_GET['campanha'] ?? null;

// Buscar campanhas distintas com data
$campanhas_stmt = $pdo->query("SELECT DISTINCT campanha, MIN(data_campanha) as primeira_data FROM (
    SELECT campanha, data_campanha FROM aberturas
    UNION ALL
    SELECT campanha, data_campanha FROM cliques
    UNION ALL
    SELECT campanha, data_campanha FROM conversoes
) AS todas WHERE campanha IS NOT NULL AND data_campanha IS NOT NULL GROUP BY campanha ORDER BY primeira_data DESC");
$campanhas = $campanhas_stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Campanhas Registradas</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f0f2f5;
      margin: 0;
      padding: 20px;
      color: #333;
    }
    h2, h3 {
      color: #2c3e50;
    }
    ul {
      list-style: none;
      padding-left: 0;
      max-width: 600px;
      margin: 0 auto 20px;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    ul li {
      padding: 12px 20px;
      border-bottom: 1px solid #eee;
    }
    ul li:last-child {
      border-bottom: none;
    }
    a {
      color: #3498db;
      text-decoration: none;
      font-weight: 600;
    }
    a:hover {
      text-decoration: underline;
    }
    .stats {
      max-width: 600px;
      background: #fff;
      margin: 0 auto;
      padding: 20px 30px;
      border-radius: 8px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.1);
    }
    .stats p {
      font-size: 1.1rem;
      margin: 10px 0;
    }
    hr {
      max-width: 600px;
      margin: 30px auto;
      border: none;
      border-top: 1px solid #ddd;
    }
  </style>
</head>
<body>

<?php if (!empty($_SESSION['mensagem_sucesso'])): ?>
  <div style="background:#d4edda; color:#155724; padding:15px; border:1px solid #c3e6cb; border-radius:5px; max-width:600px; margin:20px auto;">
    <?= htmlspecialchars($_SESSION['mensagem_sucesso']) ?>
  </div>
  <?php unset($_SESSION['mensagem_sucesso']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['erro_exclusao'])): ?>
  <div style="background:#f8d7da; color:#721c24; padding:15px; border:1px solid #f5c6cb; border-radius:5px; max-width:600px; margin:20px auto;">
    <?= htmlspecialchars($_SESSION['erro_exclusao']) ?>
  </div>
  <?php unset($_SESSION['erro_exclusao']); ?>
<?php endif; ?>

<h2 style="text-align:center;">
  Campanhas Registradas<br>
  <a href="gerador.php" style="font-size: 0.9rem; font-weight: normal; color: #007bff; text-decoration: underline;">
    ➕ Acessar Gerador de Códigos
  </a>
</h2>
  <ul>
  <?php foreach ($campanhas as $camp): 
      $nome = htmlspecialchars($camp['campanha']);
      $data = htmlspecialchars($camp['primeira_data']);
  ?>
    <li><a href='?campanha=<?= urlencode($nome) ?>'><?= $nome ?></a> - Criada em: <?= $data ?></li>
  <?php endforeach; ?>
  </ul>

  <hr>

<?php if ($campanha_atual):  
    $campanha_atual = htmlspecialchars($campanha_atual);

    $aberturas = $pdo->prepare("SELECT COUNT(*) FROM aberturas WHERE campanha = :campanha");
    $aberturas->execute(['campanha' => $campanha_atual]);
    $total_aberturas = $aberturas->fetchColumn();

    $cliques = $pdo->prepare("SELECT COUNT(*) FROM cliques WHERE campanha = :campanha");
    $cliques->execute(['campanha' => $campanha_atual]);
    $total_cliques = $cliques->fetchColumn();

    $conversoes = $pdo->prepare("SELECT COUNT(*) FROM conversoes WHERE campanha = :campanha");
    $conversoes->execute(['campanha' => $campanha_atual]);
    $total_conversoes = $conversoes->fetchColumn();
?>
  <style>
    .details-table {
      width: 100%;
      border-collapse: collapse;
      margin: 10px 0;
    }
    .details-table th, .details-table td {
      border: 1px solid #ccc;
      padding: 8px;
      text-align: left;
    }
    .details-table th {
      background-color: #f2f2f2;
    }
    .details {
      margin-bottom: 20px;
    }
  </style>

<div class="stats">
  <h3>Dados da campanha: <strong><?= $campanha_atual ?></strong></h3>

  <!-- Aberturas -->
  <p>
    <strong>Aberturas:</strong> <?= $total_aberturas ?>
    [<a href="#" onclick="toggleDetails('aberturas'); return false;">Detalhes</a>]
  </p>
  <div id="aberturas" class="details" style="display:none;">
    <table class="details-table">
      <thead>
        <tr>
          <th>Data e Hora</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $detalhes = $pdo->prepare("SELECT data_hora FROM aberturas WHERE campanha = :campanha ORDER BY data_hora DESC");
          $detalhes->execute(['campanha' => $campanha_atual]);
          foreach ($detalhes as $linha) {
              echo "<tr><td>{$linha['data_hora']}</td></tr>";
          }
        ?>
      </tbody>
    </table>
  </div>

  <!-- Cliques -->
  <p>
    <strong>Cliques:</strong> <?= $total_cliques ?>
    [<a href="#" onclick="toggleDetails('cliques'); return false;">Detalhes</a>]
  </p>
  <div id="cliques" class="details" style="display:none;">
    <table class="details-table">
      <thead>
        <tr>
          <th>URL</th>
          <th>Data e Hora</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $detalhes = $pdo->prepare("SELECT url, data_hora FROM cliques WHERE campanha = :campanha ORDER BY data_hora DESC");
          $detalhes->execute(['campanha' => $campanha_atual]);
          foreach ($detalhes as $linha) {
              echo "<tr><td>{$linha['url']}</td><td>{$linha['data_hora']}</td></tr>";
          }
        ?>
      </tbody>
    </table>
  </div>

  <!-- Conversões -->
  <p>
    <strong>Conversões:</strong> <?= $total_conversoes ?>
    [<a href="#" onclick="toggleDetails('conversoes'); return false;">Detalhes</a>]
  </p>
  <div id="conversoes" class="details" style="display:none;">
    <table class="details-table">
      <thead>
        <tr>
          <th>URL</th>
          <th>Data e Hora</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $detalhes = $pdo->prepare("SELECT url, data_hora FROM conversoes WHERE campanha = :campanha ORDER BY data_hora DESC");
          $detalhes->execute(['campanha' => $campanha_atual]);
          foreach ($detalhes as $linha) {
              echo "<tr><td>{$linha['url']}</td><td>{$linha['data_hora']}</td></tr>";
          }
        ?>
      </tbody>
    </table>
  </div>

  <!-- Botões -->
  <div style="margin-top: 30px; display: flex; gap: 10px;">
    <form method="post" onsubmit="return confirm('Tem certeza que deseja excluir esta campanha?');">
      <input type="hidden" name="excluir_campanha" value="<?= htmlspecialchars($campanha_atual) ?>">
      <button type="submit" style="background:#dc3545; color:white; border:none; padding:10px 15px; border-radius:4px; cursor:pointer; font-size: 0.9rem; display: inline-block;">
        ✕ Excluir Campanha
      </button>
    </form>

	<a href="index.php" style="background:#6c757d; color:white; border:none; padding:10px 15px; border-radius:4px; cursor:pointer; font-size:0.9rem;">
	  ← Voltar
	</a>

  </div>
</div>

  <script>
    function toggleDetails(id) {
      const el = document.getElementById(id);
      el.style.display = (el.style.display === 'none') ? 'block' : 'none';
    }
  </script>
<?php endif; ?>

</body>
</html>