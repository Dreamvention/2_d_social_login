var gulp           = require('gulp'),
    sass           = require('gulp-sass'),
    browserSync    = require('browser-sync'),
    cleanCSS       = require('gulp-clean-css'),
    autoprefixer   = require('gulp-autoprefixer');

var id_extension = 'd_social_login';
// Обновление страниц сайта на локальном сервере
gulp.task('browser-sync', function() {
    browserSync({
        proxy: "localhost",
        notify: false
    });
});

// Компиляция stylesheet.css
gulp.task('sass', function() {
    return gulp.src('admin/view/theme/default/stylesheet/d_social_login/styles.scss')
        .pipe(autoprefixer(['last 15 versions']))
        .pipe(sass().on('error', sass.logError))
        .pipe(cleanCSS())
        .pipe(gulp.dest('catalog/view/theme/default/stylesheet/d_social_login'))
        .pipe(browserSync.reload({stream: true}))
});

// Наблюдение за файлами
gulp.task('watch', ['sass', 'browser-sync'], function() {
    gulp.watch('catalog/view/theme/default/stylesheet/**/*.scss', ['sass']);
    gulp.watch('catalog/view/theme/default/template/**/*.twig', browserSync.reload);
    gulp.watch('catalog/view/theme/default/js/**/*.js', browserSync.reload);
    gulp.watch('catalog/view/theme/default/libs/**/*', browserSync.reload);
});

gulp.task('default', ['watch']);