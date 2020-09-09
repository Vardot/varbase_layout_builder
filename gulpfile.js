let gulp = require('gulp'),
  sass = require('gulp-sass'),
  postcss = require('gulp-postcss'),
  csscomb = require('gulp-csscomb'),
  cssimport = require('gulp-cssimport'),
  autoprefixer = require('autoprefixer'),
  browserSync = require('browser-sync').create()

const paths = {
  scss: {
    src: 'scss/**/*.scss',
    dest: 'css',
    watch: 'scss/**/*.scss'
  },
  js: {  }
}

// Compile SCSS into CSS.
function compile () {
  var sassOptions = {
    outputStyle: 'expanded',
    indentType: 'space',
    indentWidth: 2,
    linefeed: 'lf'
  };

  return gulp.src([paths.scss.src])
    .pipe(sass(sassOptions).on('error', sass.logError))
    .pipe(postcss([autoprefixer({
      browsers: [
        'Chrome >= 35',
        'Firefox >= 38',
        'Edge >= 12',
        'Explorer >= 10',
        'iOS >= 8',
        'Safari >= 8',
        'Android 2.3',
        'Android >= 4',
        'Opera >= 12']
    })]))
    .pipe(csscomb())
    .pipe(gulp.dest(paths.scss.dest))
    .pipe(browserSync.stream())
}

// Watching scss files.
function watch () {
  gulp.watch([paths.scss.watch], compile)
}

const build = gulp.series(compile, gulp.parallel(watch))

exports.compile = compile
exports.watch = watch

exports.default = build

// Claro
const claro_paths = {
  scss: {
    src: 'themes/claro/scss/**/*.scss',
    dest: 'themes/claro/css',
    watch: 'themes/claro/scss/**/*.scss'
  },
  js: {  }
}

// Compile Claro SCSS into CSS.
function claro_compile () {
  var sassOptions = {
    outputStyle: 'expanded',
    indentType: 'space',
    indentWidth: 2,
    linefeed: 'lf'
  };
  
  var claro_path = process.cwd();
  claro_path = claro_path.replace("/modules/contrib/varbase_layout_builder", "");
  claro_path = claro_path + '/core/themes/claro/css/';

  var claro_cssimport_options = {
    includePaths: [
      claro_path
    ],
    matchPattern: "*.css",
    matchOptions: {
      matchBase: true
    }
  };

  return gulp.src([claro_paths.scss.src])
    .pipe(sass(sassOptions).on('error', sass.logError))
    .pipe(cssimport(claro_cssimport_options))
    .pipe(postcss([autoprefixer({
      browsers: [
        'Chrome >= 35',
        'Firefox >= 38',
        'Edge >= 12',
        'Explorer >= 10',
        'iOS >= 8',
        'Safari >= 8',
        'Android 2.3',
        'Android >= 4',
        'Opera >= 12']
    })]))
    .pipe(csscomb())
    .pipe(gulp.dest(claro_paths.scss.dest))
}

// Watching Claro SCSS files.
function claro_watch () {
  gulp.watch([claro_paths.scss.watch], claro_compile)
}

const claro_build = gulp.series(claro_compile, gulp.parallel(claro_watch))

exports.claro_compile = claro_compile
exports.claro_watch = claro_watch

exports.claro_default = claro_build
