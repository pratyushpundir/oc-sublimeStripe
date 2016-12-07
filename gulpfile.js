/**
 * ------------------------------------------------------------------------
 * Gulp Setup for SublimeStripe OctoberCMS Plugin
 * Version: 1.0.1
 * Author: Pratyush Pundir | pratyushpundir@icloud.com | https://www.sublimearts.me
 * License: MIT
 * ------------------------------------------------------------------------
 */


/**
 * Getting the required packages
 * @type {[type]}
 */
var gulp = require('gulp'),
    notify = require("gulp-notify"),
    babel = require("gulp-babel"),
    concatJS = require("gulp-concat"),
    minifyJS = require("gulp-uglify"),
    bust = require("gulp-buster"),
    watch = require('gulp-watch');


/**
 * Some Configuration
 * @type {Object}
 */
var config = {
    'version': '1.0.1',
    'srcDir': './assets/src/js',
    'distDir': './assets/dist/js'
}


gulp.task('js', function() {
    return gulp.src([
            config.srcDir + '/vendor/vue-2.1.3.js',
            config.srcDir + '/sublimestripe-' + config.version + '.js'
        ])
        .pipe(concatJS('/bundle.js'))
        // .pipe(babel({
        //     presets: ['es2015']
        // }))
        .pipe(minifyJS())
        .pipe(notify('JS concatenated!'))
        .pipe(gulp.dest(config.distDir));
});

gulp.task('watch', function() {
    gulp.watch([
        config.srcDir + '/**/*'
    ], ['js']);
});

gulp.task('default', ['js', 'watch']);
