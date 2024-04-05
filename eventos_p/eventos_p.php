<?php
/**
 * Plugin Name: gerenciador de eventos curadoriais 
 * Description: Pluggin desenvolvido com o propósito de estudar o desenvolvimentos de pluggins wordpress.
 * Version: 1.0
 * Requires at least: 5.6
 * Author: Davi Oliveira e Marcos Ribeiro
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: gerenciador-eventos
 * Domain Path: /languages
 */

// Função para criar as páginas no painel de administração
function criar_paginas_admin() {
    add_menu_page(
        'Gerenciador de Eventos',
        'Eventos',
        'manage_options',
        'gerenciador-eventos',
        'exibir_pagina_eventos',
        'dashicons-calendar-alt'
    );

    add_submenu_page(
        'gerenciador-eventos',
        'Cadastro de Eventos',
        'Cadastro de Eventos',
        'manage_options',
        'cadastro-eventos',
        'exibir_formulario_adicionar_evento'
    );

    add_submenu_page(
        'gerenciador-eventos',
        'Eventos Cadastrados',
        'Eventos Cadastrados',
        'manage_options',
        'eventos-cadastrados',
        'exibir_tabela_eventos'
    );

    add_submenu_page(
        'gerenciador-eventos',
        'Cadastro de Temas',
        'Cadastro de Temas',
        'manage_options',
        'cadastro-temas',
        'exibir_formulario_criar_tema'
    );

    add_submenu_page(
        'gerenciador-eventos',
        'Cadastro de Subtemas',
        'Cadastro de Subtemas',
        'manage_options',
        'cadastro-subtemas',
        'exibir_formulario_criar_subtema'
    );
}
add_action('admin_menu', 'criar_paginas_admin');

// Função para exibir a página de gerenciamento de eventos
function exibir_pagina_eventos() {
    ?>
    <div class="wrap">
        <h1>Gerenciador de Eventos</h1>
        <h2 class="nav-tab-wrapper">
            <a href="?page=gerenciador-eventos&tab=eventos" class="nav-tab <?php echo isset($_GET['tab']) && $_GET['tab'] == 'eventos' ? 'nav-tab-active' : ''; ?>">Eventos Cadastrados</a>
            <a href="?page=gerenciador-eventos&tab=cadastro" class="nav-tab <?php echo isset($_GET['tab']) && $_GET['tab'] == 'cadastro' ? 'nav-tab-active' : ''; ?>">Cadastro de Eventos</a>
            <a href="?page=gerenciador-eventos&tab=temas" class="nav-tab <?php echo isset($_GET['tab']) && $_GET['tab'] == 'temas' ? 'nav-tab-active' : ''; ?>">Cadastro de Temas</a>
            <a href="?page=gerenciador-eventos&tab=subtemas" class="nav-tab <?php echo isset($_GET['tab']) && $_GET['tab'] == 'subtemas' ? 'nav-tab-active' : ''; ?>">Cadastro de Subtemas</a>
        </h2>

        <?php
        if (isset($_GET['tab']) && $_GET['tab'] == 'cadastro') {
            exibir_formulario_adicionar_evento();
        } elseif (isset($_GET['tab']) && $_GET['tab'] == 'eventos') {
            exibir_tabela_eventos();
        } elseif (isset($_GET['tab']) && $_GET['tab'] == 'temas') {
            exibir_formulario_criar_tema();
        } elseif (isset($_GET['tab']) && $_GET['tab'] == 'subtemas') {
            exibir_formulario_criar_subtema();
        }
        ?>
    </div>

    <?php
}

// Função para exibir o formulário de adicionar evento
function exibir_formulario_adicionar_evento() {
    global $wpdb;
    $table_temas = $wpdb->prefix . 'temas';
    $table_subtemas = $wpdb->prefix . 'subtemas';
    $temas = $wpdb->get_results("SELECT * FROM $table_temas");
    $subtemas = $wpdb->get_results("SELECT * FROM $table_subtemas");
    ?>
    <div class="wrap">
        <h1>Cadastro de Eventos</h1>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="adicionar_evento">
            <!-- Campos do formulário de evento -->
            <label for="nome">Nome:</label><br>
            <input type="text" id="nome" name="nome" required><br>
            <label for="tipo">Tipo:</label><br>
            <select id="tipo" name="tipo" required>
                <option value="Longa Duração">Longa Duração</option>
                <option value="Temporária">Temporária</option>
            </select><br>
            <label for="descricao">Descrição:</label><br>
            <textarea id="descricao" name="descricao" required></textarea><br>
            <label for="inicio">Início:</label><br>
            <input type="date" id="inicio" name="inicio" required><br>
            <label for="fim">Fim:</label><br>
            <input type="date" id="fim" name="fim" required><br>
            <!-- Seleção de Tema -->
            <label for="tema">Tema:</label><br>
            <select id="tema" name="tema" required>
                <option value="">Selecionar Tema</option>
                <?php foreach ($temas as $tema) : ?>
                    <option value="<?php echo $tema->id; ?>"><?php echo $tema->Nome; ?></option>
                <?php endforeach; ?>
            </select><br>
            <!-- Seleção de Subtema -->
            <label for="subtema">Subtema:</label><br>
            <select id="subtema" name="subtema">
                <option value="">Selecionar Subtema</option>
                <?php foreach ($subtemas as $subtema) : ?>
                    <option value="<?php echo $subtema->id; ?>"><?php echo $subtema->Nome; ?></option>
                <?php endforeach; ?>
            </select><br><br>
            <!-- Botões para cadastrar temas e subtemas -->
            <input type="submit" value="Adicionar Evento">
        </form>
    </div>
    <?php
}

// Função para exibir o formulário de adicionar tema
function exibir_formulario_criar_tema() {
    ?>
    <div class="wrap">
        <h1>Cadastro de Temas</h1>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" id="form-criar-tema">
            <input type="hidden" name="action" value="criar_tema">
            <label for="nome-tema">Nome do Tema:</label><br>
            <input type="text" id="nome-tema" name="nome-tema" required><br><br>
            <input type="submit" value="Cadastrar Tema">
        </form>
    </div>
    <?php
}

// Função para exibir o formulário de adicionar subtema
function exibir_formulario_criar_subtema() {
    ?>
    <br>
    <h1>Cadastro de Subtemas</h1>
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" id="form-criar-subtema">
        <input type="hidden" name="action" value="criar_subtema">
        <label for="nome-subtema">Nome do Subtema:</label><br>
        <input type="text" id="nome-subtema" name="nome-subtema" required><br><br>
        <input type="submit" value="Cadastrar Subtema">
    </form>
    <?php
}

// Função para processar o formulário de adicionar evento
function processar_formulario_adicionar_evento() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        global $wpdb;
        $table_name = $wpdb->prefix . 'eventos';

        $nome = sanitize_text_field($_POST['nome']);
        $tipo = sanitize_text_field($_POST['tipo']);
        $descricao = sanitize_textarea_field($_POST['descricao']);
        $inicio = sanitize_text_field($_POST['inicio']);
        $fim = sanitize_text_field($_POST['fim']);

        $wpdb->insert(
            $table_name,
            array(
                'nome' => $nome,
                'tipo' => $tipo,
                'descricao' => $descricao,
                'inicio' => $inicio,
                'fim' => $fim,
            ),
            array(
                '%s', // nome, tipo, descricao, inicio, fim são strings
                '%s',
                '%s',
                '%s',
                '%s',
            )
        );

        wp_redirect(admin_url('admin.php?page=gerenciador-eventos'));
        exit;
    }
}

add_action('admin_post_adicionar_evento', 'processar_formulario_adicionar_evento');

// Função para processar o formulário de cadastrar tema
function processar_formulario_criar_tema() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        global $wpdb;
        $table_name = $wpdb->prefix . 'temas';

        $nome_tema = sanitize_text_field($_POST['nome-tema']);

        $wpdb->insert(
            $table_name,
            array('nome' => $nome_tema),
            array('%s')
        );

        wp_redirect(admin_url('admin.php?page=gerenciador-eventos'));
        exit;
    }
}
add_action('admin_post_criar_tema', 'processar_formulario_criar_tema');

// Função para processar o formulário de cadastrar subtema
function processar_formulario_criar_subtema() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        global $wpdb;
        $table_name = $wpdb->prefix . 'subtemas';

        $nome_subtema = sanitize_text_field($_POST['nome-subtema']);

        $wpdb->insert(
            $table_name,
            array('nome' => $nome_subtema),
            array('%s')
        );

        wp_redirect(admin_url('admin.php?page=gerenciador-eventos'));
        exit;
    }
}

add_action('admin_post_criar_subtema', 'processar_formulario_criar_subtema');

// Adiciona um menu no painel de administração
// Função para exibir a tabela de eventos cadastrados
function exibir_tabela_eventos() {
    global $wpdb;
    $table_eventos = $wpdb->prefix . 'eventos';
    $eventos = $wpdb->get_results("SELECT * FROM $table_eventos");
    ?>
    <h2>Eventos Cadastrados</h2>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Tipo</th>
                <th>Descrição</th>
                <th>Início</th>
                <th>Fim</th>
                <th>Tema</th>
                <th>Subtema</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($eventos as $evento) : ?>
                <tr>
                    <td><?php echo $evento->id; ?></td>
                    <td><?php echo $evento->nome; ?></td>
                    <td><?php echo $evento->tipo; ?></td>
                    <td><?php echo $evento->descricao; ?></td>
                    <td><?php echo $evento->inicio; ?></td>
                    <td><?php echo $evento->fim; ?></td>
                    <td><?php echo obter_nome_tema_por_id($evento->tema_id); ?></td>
                    <td><?php echo obter_nome_subtema_por_id($evento->subtema_id); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
}

// Função auxiliar para obter o nome do tema por ID
function obter_nome_tema_por_id($tema_id) {
    global $wpdb;
    $table_temas = $wpdb->prefix . 'temas';
    $tema = $wpdb->get_row($wpdb->prepare("SELECT nome FROM $table_temas WHERE id = %d", $tema_id));
    return $tema ? $tema->Nome : 'N/A';
}

// Função auxiliar para obter o nome do subtema por ID
function obter_nome_subtema_por_id($subtema_id) {
    global $wpdb;
    $table_subtemas = $wpdb->prefix . 'subtemas';
    $subtema = $wpdb->get_row($wpdb->prepare("SELECT nome FROM $table_subtemas WHERE id = %d", $subtema_id));
    return $subtema ? $subtema->Nome : 'N/A';
}

// Função para exibir a página de gerenciamento de eventos
function exibir_pagina() {
    ?>
    <div class="wrap">
        <h1>Gerenciador de Eventos</h1>
        <h2 class="nav-tab-wrapper">
            <a href="?page=gerenciador-eventos&tab=cadastro" class="nav-tab <?php echo isset($_GET['tab']) && $_GET['tab'] == 'cadastro' ? 'nav-tab-active' : ''; ?>">Cadastro de Eventos</a>
            <a href="?page=gerenciador-eventos&tab=eventos" class="nav-tab <?php echo isset($_GET['tab']) && $_GET['tab'] == 'eventos' ? 'nav-tab-active' : ''; ?>">Eventos Cadastrados</a>
        </h2>

        <?php
        if (isset($_GET['tab']) && $_GET['tab'] == 'cadastro') {
            exibir_formulario_adicionar_evento();
        } elseif (isset($_GET['tab']) && $_GET['tab'] == 'eventos') {
            exibir_tabela_eventos();
        }
        ?>
    </div>
    <?php
}
function mostrar_eventos() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'eventos';
    $eventos = $wpdb->get_results("SELECT * FROM $table_name");

    $output = '<h2>Eventos Cadastrados</h2>';

    foreach ($eventos as $evento) {
        
        $output .= "
            <div class='evento-card'>
                <h3>{$evento->nome}</h3>
                <p><strong>Descrição:</strong> {$evento->descricao}</p>
                <p><strong>Início:</strong> {$evento->inicio}</p>
                <p><strong>Fim:</strong> {$evento->fim}</p>
            </div>
        ";
    }

    return $output;
}

add_shortcode('mostrar_eventos', 'mostrar_eventos');


// Adiciona um menu no painel de administração

