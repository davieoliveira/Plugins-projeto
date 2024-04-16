jQuery(document).ready(function($) {
    // Função para abrir o modal
    function abrirModal(modalId) {
        $('#' + modalId).css('display', 'block');
    }

    // Função para fechar o modal
    function fecharModal(modalId) {
        $('#' + modalId).css('display', 'none');
    }

    // Ao clicar no botão de adicionar tema
    $('#open-theme-modal').click(function() {
        abrirModal('add-theme-modal');
    });

    // Ao clicar no botão de adicionar subtema
    $('#open-subtema-modal').click(function() {
        abrirModal('add-subtema-modal');
    });

    // Ao clicar no botão de fechar dentro do modal
    $('.close').click(function() {
        fecharModal($(this).closest('.modal').attr('id'));
    });

    // Ao clicar fora do modal, fechar o modal
    $(window).click(function(event) {
        if ($(event.target).hasClass('modal')) {
            fecharModal($(event.target).attr('id'));
        }
    });
});
