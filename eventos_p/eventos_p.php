<?php
/*
Plugin Name: Meu Plugin de Cadastro
Description: Um plugin para adicionar um formulário de cadastro.
Version: 1.0
Author: Seu Nome
*/

// Função para renderizar o formulário de cadastro
function renderizar_formulario_cadastro() {
    ?>
    <div class="formulario-container">
        <form id="formulario-cadastro" method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
            <input type="hidden" name="action" value="processar_formulario_cadastro">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>
            <label for="telefone">Telefone:</label>
            <input type="tel" id="telefone" name="telefone" required><br>
            <input type="submit" value="Cadastrar">
        </form>
    </div>

    <style>
        /* Estilos para o formulário */
        .formulario-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        #formulario-cadastro label {
            display: block;
            margin-bottom: 10px;
        }

        #formulario-cadastro input[type="text"],
        #formulario-cadastro input[type="email"],
        #formulario-cadastro input[type="tel"],
        #formulario-cadastro input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        #formulario-cadastro input[type="submit"] {
            background-color: #0073e6;
            color: #fff;
            cursor: pointer;
        }

        #formulario-cadastro input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
    <?php
}

// Função para processar o formulário de cadastro
function processar_formulario_cadastro() {
    if ( isset( $_POST['nome'] ) && isset( $_POST['email'] ) && isset( $_POST['telefone'] ) ) {
        $nome = sanitize_text_field( $_POST['nome'] );
        $email = sanitize_email( $_POST['email'] );
        $telefone = sanitize_text_field( $_POST['telefone'] );

        // Aqui você pode adicionar a lógica para salvar os dados no banco de dados ou fazer qualquer outra coisa que desejar com os dados do formulário

        // Por exemplo, vamos apenas imprimir os dados por agora
        echo "Nome: $nome<br>";
        echo "Email: $email<br>";
        echo "Telefone: $telefone<br>";
    }

    // Você também pode redirecionar o usuário para uma página específica após o processamento do formulário
    // Por exemplo: wp_redirect( home_url() );
    // exit;
}

// Adicionar hooks para renderizar e processar o formulário
add_shortcode( 'formulario_cadastro', 'renderizar_formulario_cadastro' );
add_action( 'admin_post_processar_formulario_cadastro', 'processar_formulario_cadastro' );
add_action( 'admin_post_nopriv_processar_formulario_cadastro', 'processar_formulario_cadastro' );
?>
