'use strict'

gulp = require 'gulp'
$ = require('gulp-load-plugins')()
browserSync = require 'browser-sync'
reload = browserSync.reload
notifier = require 'node-notifier'
paths =
  app: 'app'
  dest: 'dist'
  src: 'src'
  scripts:
    src: 'app/scripts/*.coffee'
    dest: 'dist/scripts/*.js'
  html:
    src: 'app/jade/*.jade'
    dest: 'dist/*.html'
  style:
    src: 'app/style/{,**/}*.scss'
    dest: 'dist/style/*.css'

gulp.task 'watch', ->
  gulp.watch paths.style.src ,['scss']
  gulp.watch paths.html.src, ['jade']
  gulp.watch paths.html.dest, reload

errorHandler = (error) ->
  notifier.notify {
    message: error.message
    title: error.plugin
    sound: 'Glass'
  }
gulp.task 'jade', ->
  gulp.src paths.html.src
      .pipe $.plumber()
      .pipe $.jade
      .pipe $.htmlhint()
      .pipe $.prettify {indent_size:2}
      .pipe gulp.dest 'dist/'

gulp.task 'scss', ->
  gulp.src paths.style.src
    .pipe $.plumber()
    .pipe $.sass().on 'error', $.sass.logError
    .pipe gulp.dest paths.style.dest

gulp.task 'serve', ->
  browserSync({
    notify: false
    server: {
      baseDir: "dist"
    }
  })

gulp.task 'default', [
  'watch'
  'serve'
  'jade'
  'scss'
]
