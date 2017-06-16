var gulp = require('gulp'),
    configLocal = require('./gulp-config.json'),
    merge = require('merge'),
    sass = require('gulp-sass'),
    rename = require('gulp-rename'),
    scsslint = require('gulp-scss-lint'),
    autoprefixer = require('gulp-autoprefixer'),
    cleanCSS = require('gulp-clean-css'),
    include = require('gulp-include'),
    eslint = require('gulp-eslint'),
    isFixed = require('gulp-eslint-if-fixed'),
    uglify = require('gulp-uglify'),
    readme = require('gulp-readme-to-markdown'),
    browserSync = require('browser-sync').create();

var configDefault = {
    src: {
      scssPath: './src/scss',
      jsPath: './src/js',
    },
    dist: {
      cssPath: './static/css',
      jsPath: './static/js'
    }
  },
  config = merge(configDefault, configLocal);


//
// CSS
//

// Lint all scss files
gulp.task('scss-lint', function() {
  return gulp.src(config.src.scssPath + '/*.scss')
    .pipe(scsslint());
});

// Compile + bless primary scss files
gulp.task('css-main', function() {
  return gulp.src(config.src.scssPath + '/ucf-alert.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(cleanCSS())
    .pipe(autoprefixer({
      browsers: ['last 2 versions'],
      cascade: false
    }))
    .pipe(rename('ucf-alert.min.css'))
    .pipe(gulp.dest(config.dist.cssPath))
    .pipe(browserSync.stream());
});

// All css-related tasks
gulp.task('css', ['scss-lint', 'css-main']);


//
// JS
//

// Run eshint on js files in config.src.jsPath. Do not perform linting
// on vendor js files.
gulp.task('es-lint', function() {
    var files = [
      config.src.jsPath + '/*.js'
  ];
  return gulp.src(files)
    .pipe(eslint({ fix: true }))
    .pipe(eslint.format())
    .pipe(isFixed(config.src.jsPath));
});

// Concat and uglify js
gulp.task('js-main', function() {
  return gulp.src(config.src.jsPath + '/ucf-alert.js')
    .pipe(include())
      .on('error', console.log)
    .pipe(uglify())
    .pipe(rename('ucf-alert.min.js'))
    .pipe(gulp.dest(config.dist.jsPath))
    .pipe(browserSync.stream());
});

// All css-related tasks
gulp.task('js', ['es-lint', 'js-main']);


//
// Readme
//

// Create a Github-flavored markdown file from the plugin readme.txt
gulp.task('readme', function() {
  return gulp.src(['readme.txt'])
    .pipe(readme({
      details: false,
      screenshot_ext: [],
    }))
    .pipe(gulp.dest('.'));
});


// Rerun tasks when files change
gulp.task('watch', function() {
  if (config.sync) {
    browserSync.init({
        proxy: {
          target: config.target
        }
    });
  }

  gulp.watch(config.src.scssPath + '/**/*.scss', ['css']).on('change', browserSync.reload);
  gulp.watch(config.src.jsPath + '/**/*.js', ['js']).on('change', browserSync.reload);
  gulp.watch('./**/*.php').on('change', browserSync.reload);
  gulp.watch('readme.txt', ['readme']);
});

// Default task
gulp.task('default', ['css', 'js', 'readme']);
