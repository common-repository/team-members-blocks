/*=========================================
                SHORTCODE
==========================================*/
(function($) {

  var Grps = names;

  tinymce.create('tinymce.plugins.tf_members', {
    init: function(ed, url) {
      ed.addButton('tf_members', {
        title: 'Team Members',
        icon: 'tf-n dashicons-before dashicons-slides',
        cmd: 'tf_members_cmd'
      });

      ed.addCommand('tf_members_cmd', function() {
        ed.windowManager.open(
          //  Window Properties
          {
            file: url + '/../../core/tf_members-insert.html',
            title: 'Insert Members',
            width: 370,
            height: 350,
            inline: 1
          },
          //  Windows Parameters/Arguments
          {
            editor: ed,
            groups: Grps,
            jquery: $ // PASS JQUERY
          }
        );
      });
    }
  });
  tinymce.PluginManager.add('tf_members', tinymce.plugins.tf_members);
})(jQuery);
