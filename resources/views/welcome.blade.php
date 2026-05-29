<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'FarmaGo') }}</title>

        <style>
            :root {
                color-scheme: light;
                font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
                --brand: #047857;
                --brand-dark: #065f46;
                --ink: #10201a;
                --muted: #5d6f67;
                --surface: #f5faf7;
                --line: #d8e6df;
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                min-height: 100vh;
                background: var(--surface);
                color: var(--ink);
            }

            .page {
                min-height: 100vh;
                display: grid;
                grid-template-columns: minmax(0, 1.05fr) minmax(360px, 0.95fr);
            }

            .main {
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                padding: 48px clamp(24px, 6vw, 88px);
                background: #ffffff;
            }

            .brand {
                display: inline-flex;
                align-items: center;
                gap: 12px;
                font-size: 22px;
                font-weight: 800;
                letter-spacing: 0;
            }

            .brand-mark {
                width: 42px;
                height: 42px;
                border-radius: 12px;
                display: grid;
                place-items: center;
                background: #e3f6ec;
                color: var(--brand);
            }

            .brand-mark svg {
                width: 26px;
                height: 26px;
            }

            .content {
                max-width: 720px;
                padding: 72px 0;
            }

            h1 {
                margin: 0;
                font-size: clamp(40px, 7vw, 76px);
                line-height: 0.96;
                letter-spacing: 0;
            }

            .lead {
                margin: 24px 0 0;
                max-width: 620px;
                color: var(--muted);
                font-size: 20px;
                line-height: 1.6;
            }

            .actions {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                margin-top: 34px;
            }

            .button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-height: 46px;
                padding: 0 18px;
                border: 1px solid var(--brand);
                border-radius: 8px;
                background: var(--brand);
                color: #ffffff;
                font-weight: 700;
                text-decoration: none;
            }

            .button.secondary {
                background: #ffffff;
                color: var(--brand-dark);
            }

            .status {
                color: var(--muted);
                font-size: 14px;
            }

            .panel {
                padding: 48px clamp(24px, 5vw, 64px);
                background: #eef8f2;
                border-left: 1px solid var(--line);
                display: flex;
                flex-direction: column;
                justify-content: center;
                gap: 18px;
            }

            .metric {
                padding: 24px;
                border: 1px solid var(--line);
                border-radius: 8px;
                background: rgba(255, 255, 255, 0.78);
            }

            .metric strong {
                display: block;
                margin-bottom: 6px;
                font-size: 18px;
            }

            .metric span {
                color: var(--muted);
                line-height: 1.5;
            }

            @media (max-width: 820px) {
                .page {
                    grid-template-columns: 1fr;
                }

                .main {
                    padding: 28px 22px;
                }

                .content {
                    padding: 56px 0;
                }

                .panel {
                    border-left: 0;
                    border-top: 1px solid var(--line);
                    padding: 24px 22px 34px;
                }
            }
        </style>
    </head>
    <body>
        <div class="page">
            <main class="main">
                <div class="brand">
                    <span class="brand-mark" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M14.8 3.8a4.7 4.7 0 0 1 1.7 6.4l-4.3 7.4a4.7 4.7 0 1 1-8.1-4.7l4.3-7.4a4.7 4.7 0 0 1 6.4-1.7Z" stroke="currentColor" stroke-width="2"/>
                            <path d="M7.2 10.2 13 13.6" stroke="currentColor" stroke-width="2"/>
                            <path d="M18 14v6M15 17h6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </span>
                    {{ config('app.name', 'FarmaGo') }}
                </div>

                <section class="content" aria-labelledby="title">
                    <h1 id="title">Gestion farmaceutica lista para operar.</h1>
                    <p class="lead">
                        Controla ventas, compras, lotes, vencimientos, recetas y usuarios desde una base pensada para farmacias y boticas en Peru.
                    </p>

                    <div class="actions">
                        @auth
                            <a class="button" href="{{ url('/dashboard') }}">Ir al panel</a>
                        @else
                            <a class="button" href="{{ route('login') }}">Ingresar</a>
                            @if (Route::has('register'))
                                <a class="button secondary" href="{{ route('register') }}">Crear usuario</a>
                            @endif
                        @endauth
                    </div>
                </section>

                <p class="status">Zona horaria operativa: America/Lima</p>
            </main>

            <aside class="panel" aria-label="Modulos FarmaGo">
                <div class="metric">
                    <strong>Inventario por lote</strong>
                    <span>Stock, kardex, valorizacion y alertas por vencimiento.</span>
                </div>
                <div class="metric">
                    <strong>Ventas con receta</strong>
                    <span>Validacion de productos restringidos y salida automatica de stock.</span>
                </div>
                <div class="metric">
                    <strong>Compras trazables</strong>
                    <span>Ingreso de mercaderia, proveedores y control de modificaciones.</span>
                </div>
            </aside>
        </div>
    </body>
</html>
