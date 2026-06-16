{{-- ============================================================
     admin/_components/avatar.blade.php
     Avatar de iniciales reutilizable.

     Variables esperadas:
       $name   (string) — nombre completo del usuario
       $class  (string) — clase CSS extra (opcional)
       $size   (string) — 'sm' | 'md' | 'lg' | 'xl' (opcional)

     Uso:
       @include('admin._components.avatar', [
           'name'  => auth()->user()->name,
           'class' => 'vl-navbar__avatar',
       ])
     ============================================================ --}}

@php
  /* ── Generar iniciales ── */
  $parts    = array_filter(explode(' ', trim($name ?? 'U')));
  $initials = '';

  if (count($parts) >= 2) {
    // Primera letra del primer y segundo token
    $initials = strtoupper(substr($parts[0], 0, 1) . substr($parts[1], 0, 1));
  } else {
    // Solo una palabra: dos primeras letras
    $initials = strtoupper(substr($parts[0] ?? 'U', 0, 2));
  }

  /* ── Elegir color de gradiente según primera letra ── */
  $letter = strtolower(substr($name ?? 'a', 0, 1));
  $gradients = [
    'a' => '#2563eb, #7c3aed',
    'b' => '#0891b2, #2563eb',
    'c' => '#7c3aed, #db2777',
    'd' => '#059669, #2563eb',
    'e' => '#d97706, #dc2626',
    'f' => '#2563eb, #0891b2',
    'g' => '#7c3aed, #2563eb',
    'h' => '#dc2626, #d97706',
    'i' => '#059669, #0891b2',
    'j' => '#db2777, #7c3aed',
    'k' => '#2563eb, #059669',
    'l' => '#0891b2, #7c3aed',
    'm' => '#7c3aed, #059669',
    'n' => '#dc2626, #7c3aed',
    'o' => '#d97706, #059669',
    'p' => '#2563eb, #dc2626',
    'q' => '#059669, #d97706',
    'r' => '#db2777, #2563eb',
    's' => '#0891b2, #059669',
    't' => '#7c3aed, #d97706',
    'u' => '#2563eb, #db2777',
    'v' => '#059669, #dc2626',
    'w' => '#d97706, #7c3aed',
    'x' => '#dc2626, #0891b2',
    'y' => '#7c3aed, #0891b2',
    'z' => '#db2777, #059669',
  ];

  $gradient = $gradients[$letter] ?? '#2563eb, #7c3aed';

  /* ── Tamaños predefinidos ── */
  $sizes = [
    'sm' => ['width' => '28px', 'height' => '28px', 'font-size' => '0.65rem'],
    'md' => ['width' => '34px', 'height' => '34px', 'font-size' => '0.75rem'],
    'lg' => ['width' => '46px', 'height' => '46px', 'font-size' => '1rem'],
    'xl' => ['width' => '72px', 'height' => '72px', 'font-size' => '1.6rem'],
  ];

  $sizeKey   = $size ?? 'md';
  $sizeStyle = $sizes[$sizeKey] ?? $sizes['md'];

  $inlineStyle = implode('; ', array_map(
    fn($k, $v) => "$k: $v",
    array_keys($sizeStyle),
    $sizeStyle
  ));

  $extraClass = $class ?? '';
@endphp

<div class="{{ $extraClass }}"
     style="
       background: linear-gradient(135deg, {{ $gradient }});
       border-radius: 50%;
       display: inline-flex;
       align-items: center;
       justify-content: center;
       color: #fff;
       font-weight: 700;
       letter-spacing: .5px;
       flex-shrink: 0;
       {{ $inlineStyle }};
     "
     title="{{ $name ?? '' }}"
     aria-label="Avatar de {{ $name ?? 'usuario' }}">
  {{ $initials }}
</div>