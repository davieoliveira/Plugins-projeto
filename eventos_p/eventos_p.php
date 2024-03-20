<?php
/*
Plugin Name: Exposição de Eventos
Description: Plugin para criação de eventos de exposição de itens.
Version: 1.0
Author: Seu Nome
*/

// Adiciona o menu de administração para gerenciar os eventos
function exposicao_eventos_menu() {
    add_menu_page(
        'Eventos de Exposição',
        'Eventos de Exposição',
        'manage_options',
        'exposicao_eventos_admin',
        'exposicao_eventos_admin_page'
    );
}
add_action('admin_menu', 'exposicao_eventos_menu');

// Página de administração para gerenciar os eventos
function exposicao_eventos_admin_page() {
    ?>
    <div class="wrap">
        <h2>Eventos de Exposição</h2>
        <h3>Criar Novo Evento</h3>
        <?php formulario_criacao_evento(); ?>
        <?php exibir_modal_novo_subtema(); ?> <!-- Adicionado aqui -->
    </div>
    <?php
}

// Adiciona o formulário de criação de eventos
function formulario_criacao_evento() {
    ob_start(); ?>
    <form id="formulario-criacao-evento" method="post">
        <div class="campo">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" >
        </div>

        <div class="campo">
            <label for="tipo">Tipo:</label>
            <select id="tipo" name="tipo" requiredo">Longa Duração</option>
                <option value="temporaria">Temporária</option>
            </select>
        </div>

        <div class="campo">
            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" ></textarea>
        </div>

        <div class="campo">
            <label for="inicio">Início:</label>
            <input type="date" id="inicio" name="inicio" >
        </div>

        <div class="campo">
            <label for="fim">Fim:</label>
            <input type="date" id="fim" name="fim" >
        </div>

        <div class="campo">
            <label for="tema">Tema:</label>
            <select id="tema" name="tema" >
                <?php echo obter_opcoes_temas(); ?>
            </select>
            <button id="criar-tema">Criar Novo Tema</button>
        </div>

        <div class="campo">
            <label for="subtema">Subtema:</label>
            <select id="subtema" name="subtema">
                <!-- Os subtemas serão preenchidos dinamicamente usando JavaScript -->
            </select>
            <button id="abrir-modal-novo-subtema">Criar Novo Subtema</button>
        </div>

        <!-- Botão para abrir modal de novo subtema -->
        <button id="abrir-modal-novo-subtema">Criar Novo Subtema</button>

        <input type="submit" value="Criar Evento">
    </form>

    <!-- Modal de novo subtema -->
    <div id="modal-novo-subtema" style="display: none;">
        <form id="formulario-novo-subtema">
            <label for="nome-subtema">Nome do Subtema:</label>
            <input type="text" id="nome-subtema" name="nome-subtema">
            <input type="submit" value="Criar Subtema">
        </form>
    </div>

    <?php
    return ob_get_clean();
}

// Processa o formulário de criação de evento e salva os dados no banco de dados
function processar_formulario_criacao_evento() {
    global $wpdb;
    if ( isset($_POST['nome']) && isset($_POST['tipo']) && isset($_POST['descricao']) && isset($_POST['inicio']) && isset($_POST['fim']) && isset($_POST['tema']) ) {
        $nome = sanitize_text_field($_POST['nome']);
        $tipo = sanitize_text_field($_POST['tipo']);
        $descricao = sanitize_textarea_field($_POST['descricao']);
        $inicio = sanitize_text_field($_POST['inicio']);
        $fim = sanitize_text_field($_POST['fim']);
        $tema = sanitize_text_field($_POST['tema']);
        $subtema = isset($_POST['subtema']) ? sanitize_text_field($_POST['subtema']) : '';

        // Insere o evento no banco de dados
        $wpdb->insert(
            $wpdb->prefix . 'eventos_exposicao',
            array(
                'nome' => $nome,
                'tipo' => $tipo,
                'descricao' => $descricao,
                'inicio' => $inicio,
                'fim' => $fim,
                'tema' => $tema,
                'subtema' => $subtema
            )
        );

        // Redireciona ou exibe mensagem de sucesso
    }
}
add_action('admin_post_processar_formulario_criacao_evento', 'processar_formulario_criacao_evento');

// Adiciona o shortcode para exibir o formulário
function exposicao_eventos_formulario_shortcode() {
    return formulario_criacao_evento();
}
add_shortcode('exposicao_eventos_formulario', 'exposicao_eventos_formulario_shortcode');

// Função para obter as opções dos temas do banco de dados do WordPress
function obter_opcoes_temas() {
    global $wpdb;
    $opcoes = '';
    $temas = $wpdb->get_results("SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = 'tema'");
    if ($temas) {
        foreach ($temas as $tema) {
            $opcoes .= "<option value='$tema->meta_value'>$tema->meta_value</option>";
        }
    }
    return $opcoes;
}

// Processa o formulário para criar um novo tema
function processar_formulario_novo_tema() {
    if (isset($_POST['novo_tema'])) {
        global $wpdb;
        $novo_tema = sanitize_text_field($_POST['novo_tema']);
        $wpdb->insert(
            $wpdb->prefix . 'postmeta',
            array(
                'meta_key' => 'tema',
                'meta_value' => $novo_tema
            )
        );
    }
    wp_redirect(admin_url('admin.php?page=exposicao_eventos_admin'));
    exit;
}
add_action('admin_post_criar_novo_tema', 'processar_formulario_novo_tema');

// Processa o formulário para criar um novo subtema
function processar_formulario_novo_subtema() {
    if (isset($_POST['novo_subtema'])) {
        global $wpdb;
        $novo_subtema = sanitize_text_field($_POST['novo_subtema']);
        $wpdb->insert(
            $wpdb->prefix . 'postmeta',
            array(
                'meta_key' => 'subtema',
                'meta_value' => $novo_subtema
            )
        );
    }
    wp_redirect(admin_url('admin.php?page=exposicao_eventos_admin'));
    exit;
}
add_action('admin_post_criar_novo_subtema', 'processar_formulario_novo_subtema');

// Função para exibir modal de novo subtema
function exibir_modal_novo_subtema() {
    ?>
    <!-- Botão para abrir modal de novo subtema -->
    <button id="abrir-modal-novo-subtema">Criar Novo Subtema</button>

    <!-- Modal de novo subtema -->
    <div id="modal-novo-subtema" style="display: none;">
        <h2>Novo Subtema</h2>
        <form id="formulario-novo-subtema" method="post" action="<?php echo admin_url('admin-post.php'); ?>">
            <input type="hidden" name="action" value="criar_novo_subtema">
            <label for="novo_subtema">Nome do Subtema:</label>
            <input type="text" id="novo_subtema" name="novo_subtema">
            <input type="submit" value="Criar Subtema">
        </form>
    </div>

    <!-- Script para lidar com a abertura e submissão do modal -->
    <script>
        jQuery(document).ready(function($) {
            // Abrir modal de novo subtema
            $('#abrir-modal-novo-subtema').click(function() {
                $('#modal-novo-subtema').fadeIn();
            });

            // Fechar modal ao clicar no botão de fechar
            $('.fechar-modal').click(function() {
                $(this).closest('.modal').fadeOut();
            });
        });
    </script>
    <?php
}

// Chama a função para criar as tabelas no início da execução do plugin
register_activation_hook(__FILE__, 'criar_tabelas_banco');
// Função para criar tabelas no banco de dados
function criar_tabelas_banco() {
    global $wpdb;

    // Obtendo as configurações de conexão do WordPress
    $charset_collate = $wpdb->get_charset_collate();

    // Tabela para armazenar eventos
    $tabela_eventos = $wpdb->prefix . 'eventos_exposicao';
    if ($wpdb->get_var("SHOW TABLES LIKE '$tabela_eventos'") != $tabela_eventos) {
        $sql = "CREATE TABLE $tabela_eventos (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            nome varchar(255) NOT NULL,
            tipo varchar(50) NOT NULL,
            descricao text NOT NULL,
            inicio date NOT NULL,
            fim date NOT NULL,
            tema varchar(100) NOT NULL,
            subtema varchar(100),
            PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);
    }

    // Tabela para armazenar temas
    $tabela_temas = $wpdb->prefix . 'temas_exposicao';
    if ($wpdb->get_var("SHOW TABLES LIKE '$tabela_temas'") != $tabela_temas) {
        $sql = "CREATE TABLE $tabela_temas (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            nome varchar(255) NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);
    }

    // Tabela para armazenar subtemas
    $tabela_subtemas = $wpdb->prefix . 'subtemas_exposicao';
    if ($wpdb->get_var("SHOW TABLES LIKE '$tabela_subtemas'") != $tabela_subtemas) {
        $sql = "CREATE TABLE $tabela_subtemas (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            tema_id mediumint(9) NOT NULL,
            nome varchar(255) NOT NULL,
            PRIMARY KEY  (id),
            FOREIGN KEY (tema_id) REFERENCES $tabela_temas(id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);
    }
}
register_activation_hook(__FILE__, 'criar_tabelas_banco');

// Função para conectar-se ao banco de dados
function conectar_banco_de_dados() {
    global $wpdb;
    $db_host = 'localhost:10004';
    $db_name = 'local';
    $db_user = 'root';
    $db_password = 'root';
    $wpdb = new wpdb($db_user, $db_password, $db_name, $db_host);
}
add_action('init', 'conectar_banco_de_dados');
function carregar_jquery() {
    wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'carregar_jquery');

