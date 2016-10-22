var gulp = require('gulp'),
    uglify = require('gulp-uglify'),
    concat = require('gulp-concat'),
    minifyCss = require('gulp-minify-css');


gulp.task('frontend_js', function () {
    gulp.src(
        [
            'frontend/web/js/simpleCart.min.js',
            'frontend/web/js/jquery.chocolat.js',
            'frontend/web/js/jstarbox.js',
            'frontend/web/js/jquery.magnific-popup.js',
            'frontend/web/js/jquery.flexslider.js',
            'frontend/web/js/imagezoom.js',
            'frontend/web/js/init-vendor-scripts.js',
            'frontend/web/js/custom.js'
        ])
        .pipe(uglify())
        .pipe(concat('script.min.js'))
        .pipe(gulp.dest('frontend/web/build'))
});

gulp.task('frontend_css', function () {
    gulp.src(
        [
            'frontend/web/css/style.css',
            'frontend/web/css/style4.css',
            'frontend/web/css/chocolate.css',
            'frontend/web/css/jstarbox.css',
            'frontend/web/css/popuo-box.css',
            'frontend/web/css/form.css',
            'frontend/web/css/flexslider.css',
            'frontend/web/css/custom.css',
        ])
        .pipe(minifyCss())
        .pipe(concat('style.min.css'))
        .pipe(gulp.dest('frontend/web/build'))
});

gulp.task('default', ['frontend_js', 'frontend_css']);