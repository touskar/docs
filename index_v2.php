<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>PayTech API - Documentation</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="description" content="Documentation complète de l'API PayTech pour l'intégration de paiements en ligne au Sénégal et en Afrique de l'Ouest">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/docsify@4/lib/themes/vue.css">
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/prismjs@1/themes/prism.css">
  <style>
    :root {
      --theme-color: #135571;
      --theme-color-secondary: #2c5aa0;
      --text-color-base: #2c3e50;
      --text-color-secondary: #7f8c8d;
      --border-color: #ebedef;
      --sidebar-width: 300px;
    }

    .app-name-link img {
      width: 120px;
    }

    .sidebar {
      background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
      border-right: 1px solid var(--border-color);
    }

    .sidebar-nav li strong {
      color: var(--theme-color);
      font-weight: 600;
      font-size: 14px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .sidebar-nav a {
      color: var(--text-color-base);
      font-weight: 400;
      border-bottom: none;
      transition: all 0.3s ease;
    }

    .sidebar-nav a:hover {
      color: var(--theme-color);
      text-decoration: none;
    }

    .sidebar-nav li.active > a {
      color: var(--theme-color);
      font-weight: 600;
      border-right: 2px solid var(--theme-color);
    }

    .content {
      padding-top: 60px;
    }

    .markdown-section {
      max-width: 800px;
      margin: 0 auto;
      padding: 30px;
    }

    .markdown-section h1 {
      color: var(--theme-color);
      font-size: 2.5rem;
      font-weight: 300;
      margin-bottom: 1rem;
    }

    .markdown-section h2 {
      color: var(--theme-color);
      font-size: 1.75rem;
      font-weight: 600;
      margin-top: 2rem;
      margin-bottom: 1rem;
      padding-bottom: 0.5rem;
      border-bottom: 2px solid var(--border-color);
    }

    .markdown-section h3 {
      color: var(--theme-color-secondary);
      font-size: 1.25rem;
      font-weight: 600;
      margin-top: 1.5rem;
      margin-bottom: 0.75rem;
    }

    .markdown-section table {
      border-collapse: collapse;
      width: 100%;
      margin: 1.5rem 0;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .markdown-section table th {
      background-color: var(--theme-color);
      color: white;
      font-weight: 600;
      padding: 12px;
      text-align: left;
    }

    .markdown-section table td {
      padding: 12px;
      border-bottom: 1px solid var(--border-color);
    }

    .markdown-section table tr:hover {
      background-color: #f8f9fa;
    }

    .markdown-section pre {
      background: #f8f9fa;
      border: 1px solid var(--border-color);
      border-radius: 6px;
      padding: 1rem;
      margin: 1rem 0;
      overflow-x: auto;
    }

    .markdown-section code {
      background: #f1f3f4;
      padding: 2px 4px;
      border-radius: 3px;
      font-size: 0.9em;
      color: #e83e8c;
    }

    .markdown-section pre code {
      background: transparent;
      padding: 0;
      color: inherit;
    }

    .alert {
      padding: 1rem;
      margin: 1rem 0;
      border-radius: 6px;
      border-left: 4px solid;
    }

    .alert-info {
      background-color: #e7f3ff;
      border-left-color: #2196f3;
      color: #0c5460;
    }

    .alert-warning {
      background-color: #fff3cd;
      border-left-color: #ffc107;
      color: #856404;
    }

    .alert-success {
      background-color: #d4edda;
      border-left-color: #28a745;
      color: #155724;
    }

    .alert-danger {
      background-color: #f8d7da;
      border-left-color: #dc3545;
      color: #721c24;
    }

    .cover-main {
      background: linear-gradient(135deg, var(--theme-color) 0%, var(--theme-color-secondary) 100%);
    }

    .cover-main h1 {
      color: white;
      font-size: 3rem;
      font-weight: 300;
    }

    .cover-main p {
      color: rgba(255,255,255,0.8);
      font-size: 1.2rem;
    }

    .cover-main .btn {
      background: white;
      color: var(--theme-color);
      padding: 12px 24px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .cover-main .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .pagination-item-title {
      font-size: 16px;
      font-weight: 600;
    }

    .app-nav {
      position: fixed;
      margin: 0;
      padding: 10px 50px 10px 0;
      width: calc(100vw - 325px);
      background-color: #fff;
      height: 55px;
      border-bottom: 1px solid var(--border-color);
    }

    .github-corner:hover .octo-arm {
      animation: octocat-wave 560ms ease-in-out;
    }

    @keyframes octocat-wave {
      0%, 20%, 60%, 100% {
        transform: rotate(0);
      }
      40%, 80% {
        transform: rotate(-25deg);
      }
    }

    .docsify-copy-code-button {
      background: var(--theme-color) !important;
      color: white !important;
    }

    .token.title {
      color: var(--theme-color);
    }

    .lang-badge {
      background: var(--theme-color);
      color: white;
      padding: 2px 6px;
      border-radius: 3px;
      font-size: 0.8em;
      margin-left: 8px;
    }
  </style>
</head>
<body>
  <div id="app">Chargement...</div>
  <script>
    window.$docsify = {
      name: 'PayTech API',
      repo: 'https://github.com/paytech-sn',
      loadSidebar: true,
      loadNavbar: true,
      coverpage: true,
      onlyCover: false,
      auto2top: true,
      maxLevel: 4,
      subMaxLevel: 3,
      search: {
        maxAge: 86400000,
        paths: 'auto',
        placeholder: 'Rechercher...',
        noData: 'Aucun résultat trouvé',
        depth: 6,
        hideOtherSidebarContent: false,
      },
      copyCode: {
        buttonText: 'Copier',
        errorText: 'Erreur',
        successText: 'Copié!'
      },
      pagination: {
        previousText: 'Précédent',
        nextText: 'Suivant',
        crossChapter: true,
        crossChapterText: true,
      },
      tabs: {
        persist: true,
        sync: true,
        theme: 'classic',
        tabComments: true,
        tabHeadings: true
      },
      themeable: {
        readyTransition: true,
        responsiveTables: true
      },
      plugins: [
        function(hook, vm) {
          hook.beforeEach(function (html) {
            var url = 'https://github.com/paytech-sn/documentation/blob/main/' + vm.route.file
            var editHtml = '[:memo: Modifier ce document](' + url + ')\n'
            return editHtml + html
          })
        }
      ]
    }
  </script>
  <!-- Docsify v4 -->
  <script src="//cdn.jsdelivr.net/npm/docsify@4"></script>
  <!-- Plugins -->
  <script src="//cdn.jsdelivr.net/npm/docsify/lib/plugins/search.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/docsify-copy-code@2"></script>
  <script src="//cdn.jsdelivr.net/npm/docsify-pagination/dist/docsify-pagination.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/docsify-tabs@1"></script>
  <script src="//cdn.jsdelivr.net/npm/docsify-themeable@0"></script>
  <!-- Prism -->
  <script src="//cdn.jsdelivr.net/npm/prismjs@1/components/prism-bash.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/prismjs@1/components/prism-php.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/prismjs@1/components/prism-javascript.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/prismjs@1/components/prism-json.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/prismjs@1/components/prism-java.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/prismjs@1/components/prism-python.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/prismjs@1/components/prism-csharp.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/prismjs@1/components/prism-swift.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/prismjs@1/components/prism-kotlin.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/prismjs@1/components/prism-dart.min.js"></script>
</body>
</html>

