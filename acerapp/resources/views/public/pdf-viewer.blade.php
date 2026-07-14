<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body,
        html {
            height: 100%;
            width: 100%;
            overflow: hidden;
            background-color: #ffffff;
        }

        #pdf-viewer-container {
            width: 100vw;
            height: 100vh;
            background: #ffffff;
        }

        /* Force Hide fallbacks */
        [data-testid="main-menu-button"],
        [data-testid="annotations-sidebar-button"],
        [data-epdf-i="comment-button"] {
            display: none !important;
        }
    </style>
</head>

<body>
    <div id="pdf-viewer-container"></div>

    <script type="module">
        import EmbedPDF from 'https://cdn.jsdelivr.net/npm/@embedpdf/snippet@2/dist/embedpdf.js';

        async function loadViewer() {
            try {
                const instance = await EmbedPDF.init({
                    type: 'container',
                    target: document.getElementById('pdf-viewer-container'),
                    src: '{{ $pdfUrl }}',
                    theme: { preference: 'light' },
                    disabledCategories: [
                        'annotation',
                        'redaction',
                        'form',
                        'insert',
                        'print',
                        'export',
                        'open',
                        'document-editor'
                    ],
                    // Forcefully clean the toolbar
                    onWidgetCreated: (viewer) => {
                        viewer.setToolbarItems([
                            { type: "pager" },
                            { type: "zoom-out" },
                            { type: "zoom-in" },
                            { type: "zoom-level" },
                            { type: "spacer" },
                            { type: "search" }
                        ]);

                        // Initial Zoom 200%
                        if (viewer.setViewState) {
                            viewer.setViewState(state => state.set("zoom", 2.0));
                        }
                    }
                });

                // Zoom Backup
                setTimeout(() => {
                    if (instance && instance.setViewState) {
                        instance.setViewState(v => v.set("zoom", 2.0));
                    }
                }, 1500);

            } catch (err) {
                console.error('PDF Viewer Error:', err);
            }
        }

        loadViewer();
    </script>
</body>

</html>