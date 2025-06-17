<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerador de Códigos de Rastreamento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .copy-btn {
            margin-top: 10px;
            background-color: #28a745;
        }
        .clear-btn {
            background-color: #dc3545;
            margin-left: 10px;
        }
        pre {
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 6px;
            white-space: pre-wrap;
			word-wrap: break-word;
            margin-top: 10px;
            user-select: all;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Gerador de Códigos de Rastreamento</h1>
    <form id="formulario" method="GET">
        <label for="campanha">Nome da Campanha:</label>
        <input type="text" id="campanha" name="campanha" required>

        <label for="data">Data (AAAA-MM-DD):</label>
        <input type="date" id="data" name="data" value="<?php echo date('Y-m-d'); ?>" required>

        <label for="url_clique">URL de Destino (para clique):</label>
        <input type="url" id="url_clique" name="url_clique" required>

        <label for="url_site">URL do Site (domínio):</label>
        <input type="url" id="url_site" name="url_site" placeholder="https://seudominio.com" required>

        <button type="submit">Gerar Códigos</button>
		<button type="button" onclick="window.location.href='https://SEUDOMÍNIO.com.br/mailpainel/index.php';">Voltar</button>
        <button type="button" class="clear-btn" onclick="document.getElementById('formulario').reset()">Limpar</button>
    </form>

    <?php if (!empty($_GET['campanha'])):
        $campanha = urlencode($_GET['campanha']);
        $data = urlencode($_GET['data']);
        $url_clique = urlencode($_GET['url_clique']);
        $url_site = rtrim($_GET['url_site'], '/');
    ?>
        <h2>Códigos Gerados</h2>

		<label for="pixel-code">📩 Pixel de Abertura (Código Pixel):</label>
		<pre id="pixel-code" style="white-space: pre-wrap;"><code><?php
			$src = htmlspecialchars($url_site . '/mailpainel/pixel.php?campanha=' . urlencode($campanha) . '&data=' . urlencode($data));
			echo '&lt;img src="' . $src . '" width="1" height="1" alt="" style="display:block; border:0; outline:none; text-decoration:none;"&gt;';
		?></code></pre>
		<button class="copy-btn" onclick="copiarCodigo('pixel-code')">Copiar código</button>

        <label>🔗 Rastreamento de Cliques (URL somente):</label>
        <pre id="clique-code"><code><?php
            echo $url_site . "/mailpainel/redirect.php?campanha=" . $campanha . "&data=" . $data . "&url=";
        ?></code></pre>
        <button class="copy-btn" onclick="copiarCodigo('clique-code')">Copiar URL</button>

        <label>🎯 Rastreamento de Conversão (URL somente):</label>
        <pre id="conversao-code"><code><?php
            echo $url_site . "/mailpainel/converter.php?url=" . $url_clique . "&campanha=" . $campanha . "&data=" . $data;
        ?></code></pre>
        <button class="copy-btn" onclick="copiarCodigo('conversao-code')">Copiar URL</button>
    <?php endif; ?>
</div>

<script>
function copiarCodigo(id) {
    const el = document.getElementById(id);
    const texto = el.innerText || el.textContent;
    navigator.clipboard.writeText(texto.trim()).then(() => {
        alert("Copiado com sucesso!");
    }).catch(() => {
        alert("Erro ao copiar.");
    });
}
</script>
</body>
</html>