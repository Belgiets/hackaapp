'use strict'

let gulp = require('gulp'),
  scss = require('gulp-sass'),
  sourcemaps = require('gulp-sourcemaps'),
  uglify = require('gulp-uglify'),
  babel = require('gulp-babel'),
  concat = require('gulp-concat'),
  plumber = require('gulp-plumber'),
  imagemin = require('gulp-imagemin'),
  cleanCSS = require('gulp-clean-css'),
  concatCss = require('gulp-concat-css'),
  rename = require("gulp-rename"),
  environments = require('gulp-environments'),
  clean = require('gulp-clean')

let CSS_SRC = [
  './node_modules/bootstrap/dist/css/bootstrap.css',
  './node_modules/font-awesome/css/font-awesome.css',
  './assets/libs/bootstrap-template/css/font.css',
  './assets/libs/bootstrap-template/css/style.default.css'
]

let JS_SRC = [
  './node_modules/jquery/dist/jquery.min.js',
  './node_modules/bootstrap/dist/js/bootstrap.min.js',
  './node_modules/jquery.cookie/jquery.cookie.js',
  './assets/libs/**/js/*.js',
  './assets/js/app.js'
]

let FONTS_SRC = [
  './assets/libs/bootstrap-template/fonts/*',
  './node_modules/font-awesome/fonts/*'
]

function handleError (err) {
  console.log(err.toString())
  this.emit('end')
}

gulp.task('libs-styles', function () {
  return gulp.src(CSS_SRC)
    .pipe(concatCss("libs.min.css", {rebaseUrls: false}))
    .pipe(environments.production(cleanCSS({compatibility: 'ie8'})))
    .pipe(gulp.dest('./public/build/css'))
})

gulp.task('libs-fonts', function () {
  return gulp.src(FONTS_SRC)
    .pipe(gulp.dest('./public/build/fonts'))
})

gulp.task('styles', function () {
  return gulp.src('./assets/scss/app.scss')
    .pipe(plumber({errorHandler: handleError}))
    .pipe(sourcemaps.init())
    .pipe(scss(environments.production({outputStyle: 'compressed'})))
    .pipe(sourcemaps.write())
    .pipe(rename("app.min.css"))
    .pipe(gulp.dest('./public/build/css'))
})

gulp.task('js', function() {
  return gulp.src(JS_SRC)
    .pipe(plumber({ errorHandler: handleError }))
    .pipe(sourcemaps.init())
    .pipe(babel({compact: true}))
    .pipe(concat('app.js'))
    .pipe(environments.production(uglify()))
    .pipe(sourcemaps.write())
    .pipe(rename("app.min.js"))
    .pipe(gulp.dest('./public/build/js'));
});

gulp.task('clean', function() {
  return gulp.src('./public/build', {read: false})
    .pipe(clean())
})

gulp.task('watch', ['styles', 'js'], function () {
  gulp.watch('./assets/scss/*.scss', ['styles'])
  gulp.watch('./assets/js/*.js', ['js'])
})

gulp.task('default', ['libs-fonts', 'libs-styles', 'styles', 'js'], function () {})