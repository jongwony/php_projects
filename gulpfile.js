
var Bier = require('bier');

Bier.settings.dist_prefix = 'public/static/';

Bier(function (will) {
    will.copy('bower_components/jquery/dist/*').to('jquery');
    will.copy('bower_components/font-awesome/css/*').to('font-awesome/css');
    will.copy('bower_components/font-awesome/fonts/*').to('font-awesome/fonts');
    will.copy('bower_components/bootstrap/dist/**/*').to('bootstrap');
    will.copy('bower_components/AdminLTE/dist/**/*').to('admin-lte');

    will.sass('style/publ/**/*.scss').to('publ');
    will.sass(['style/admin/layout.scss', 'style/admin/login.scss']).to('admin/css');
    will.sass('style/errors/**/*.scss').to('errors');

    will.browserify(['script/admin/**/*.js']).to('admin/js');
});
