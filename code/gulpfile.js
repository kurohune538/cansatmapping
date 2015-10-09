var gulp = require('gulp');
var browserSync = require('browser-sync');
var reload = browserSync.reload();
var notify = require('gulp-notify');
var coffee = require('gulp-coffee');
var jade = require('gulp-jade');
var htmlhint = require('gulp-htmlhint');
var prettify = require('gulp-prettify');
var watch = require('gulp-watch');
var plumber = require('gulp-plumber');
var sass = require('gulp-sass');

var paths = {
  app: 'app',
  dest: 'dist',
  src: 'src',
  scripts: {
    src: 'app/scripts/*.coffee',
    dest: 'dist/scripts/'
  },
  html:{
    src: 'app/jade/*.jade',
    dest: 'dist/'
  },
  style:{
    src: 'app/style/{,**/}*.scss',
    dest: 'dist/style/'
  }
};

gulp.task('watch', function(){
  gulp.watch(paths.style.src,['scss']);
  gulp.watch(paths.html.src,['jade']);
  gulp.watch(paths.scripts.src,['coffee']);
  gulp.watch(paths.html.dest,['bs-reload']);
});

gulp.task('jade', function(){
  gulp.src(paths.html.src)
    .pipe(plumber({
		  errorHandler: notify.onError("Error: <%= error.message %>")
		 }))
    .pipe(jade())
    .pipe(htmlhint())
    .pipe(prettify({indent_size:2}))
    .pipe(gulp.dest(paths.dest));
});

gulp.task('scss', function(){
  gulp.src(paths.style.src)
    .pipe(plumber({
		  errorHandler: notify.onError("Error: <%= error.message %>")
		 }))
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest(paths.style.dest));
});

gulp.task('coffee', function(){
  gulp.src(paths.scripts.src)
    .pipe(plumber({
		  errorHandler: notify.onError("Error: <%= error.message %>")
		 }))
    .pipe(coffee({bare: true}))
    .pipe(gulp.dest(paths.scripts.dest));
});
gulp.task('bs-reload', function () {
    browserSync.reload();
});

<<<<<<< HEAD

=======
gulp.task('serve', function(){
  browserSync({
    notify: false,
    server: {
      baseDir: "dist"
    }
  });
});
>>>>>>> eb91f7c19bfcedb7018461c9aa9c7297f3a43f60

gulp.task('default', ['watch', 'coffee', 'jade', 'scss', 'serve']);
