<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: Arial, sans-serif; background:#f4f4f4; margin:0; padding:0; }
    .container { max-width:480px; margin:40px auto; background:#fff;
                 border-radius:8px; overflow:hidden;
                 box-shadow:0 2px 8px rgba(0,0,0,.1); }
    .header { background:linear-gradient(135deg,#2563eb,#7c3aed);
              padding:28px 32px; text-align:center; }
    .header h1 { color:#fff; margin:0; font-size:1.2rem; }
    .body { padding:32px; }
    .body p { color:#444; font-size:.95rem; line-height:1.6; }
    .codigo { text-align:center; margin:28px 0; }
    .codigo span { display:inline-block; background:#f0f4ff;
                   border:2px dashed #2563eb; border-radius:8px;
                   padding:16px 32px; font-size:2rem; font-weight:700;
                   letter-spacing:8px; color:#2563eb; }
    .aviso { background:#fff8e1; border-left:4px solid #f59e0b;
             padding:12px 16px; border-radius:4px; margin-top:20px;
             font-size:.82rem; color:#92400e; }
    .footer { text-align:center; padding:16px;
              font-size:.75rem; color:#999; background:#f9f9f9; }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>🔐 Verificación de seguridad</h1>
    </div>
    <div class="body">
      <p>Hola, <strong>{{ $nombre }}</strong>.</p>
      <p>Recibimos una solicitud para cambiar tu contraseña en el sistema
         <strong>Vaso de Leche Cusco</strong>. Usa el siguiente código:</p>

      <div class="codigo">
        <span>{{ $codigo }}</span>
      </div>

      <div class="aviso">
        ⏱ Este código expira en <strong>10 minutos</strong>.<br>
        Si no solicitaste este cambio, ignora este mensaje.
      </div>
    </div>
    <div class="footer">
      Municipalidad — Programa Vaso de Leche Cusco
    </div>
  </div>
</body>
</html>