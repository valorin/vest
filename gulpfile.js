// Define plugins/modules
var gulp         = require('gulp');
var phpspec      = require('gulp-phpspec');
var less         = require('gulp-less');
var autoprefixer = require('gulp-autoprefixer');
var minifycss    = require('gulp-minify-css');
var rimraf       = require('gulp-rimraf');
var concat       = require('gulp-concat');
var stripDebug   = require('gulp-strip-debug');
var uglify       = require('gulp-uglify');

// Run phpspec tests
gulp.task('test', function() {
   gulp.src('spec/**/*.php')
       .pipe(phpspec('./vendor/bin/phpspec run --format=dot', { noInteract: true, format: "pretty" }));
});


// JS concat, strip debugging and minify
gulp.task('scripts', function() {

    var scripts = [
        './bower_components/jquery/dist/jquery.js',
        './bower_components/bootstrap/js/*.js',
        './app/assets/scripts/*.js',
    ];

    gulp.src(scripts)
        .pipe(concat('debug.js'))
        .pipe(gulp.dest('public/assets/scripts/'));

    gulp.src(scripts)
        .pipe(concat('scripts.js'))
        .pipe(stripDebug())
        .pipe(uglify())
        .pipe(gulp.dest('public/assets/scripts/'));
});

// Compile LESS
gulp.task('less', function () {
    var styles = [
        './app/assets/less/styles.less'
    ];
    gulp.src(styles)
        .pipe(less())
        .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
        .pipe(minifycss())
        .pipe(gulp.dest('public/assets/css'));
});

// Copy assets into ./public
gulp.task('fonts', function() {
    gulp.src('public/assets/fonts', {read: false})
        .pipe(rimraf());

    gulp.src('bower_components/bootstrap/fonts/**')
        .pipe(gulp.dest('public/assets/fonts'));
});

// Watch for changes
gulp.task('watch', ['default'], function () {
    gulp.watch(['app/assets/less/*.less'], ['less']);
    gulp.watch(['./app/assets/scripts/*.js'], ['scripts']);
    // gulp.watch(['spec/**/*.php', 'app/models/*.php'], ['test']);
});

gulp.task('default', ['fonts', 'less', 'scripts']);
