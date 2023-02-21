const { src, dest, watch, series } = require("gulp");
const sass = require("gulp-sass")(require("sass"));
const postcss = require("gulp-postcss");
const cssNano = require("cssnano");
const terser = require("gulp-terser");

//Views for all Modules
function copyViews() {
  return src("../dev/views/**/*.phtml").pipe(dest("../html/App/Views/"));
}

function copyImages() {
  return src("../dev/img/**/*.{gif,png,jpg,jpeg,svg}").pipe(
    dest("../html/public/img/")
  );
}

//Translations for Site
function copySiteTrans() {
  return src("../dev/trans/**/*.json").pipe(dest("../html/App/Translations/"));
}

function scssTask() {
  return src("../dev/scss/**/*.scss")
    .pipe(sass())
    .pipe(postcss([cssNano()]))
    .pipe(dest("../html/public/css"));
}

function jsTask() {
  return src("../dev/js/**/*.js")
    .pipe(terser())
    .pipe(dest("../html/public/js"));
}

function watchTask() {
  watch("../dev/views/**/*.phtml", copyViews);
  watch("../dev/views/site/**/*.json", copySiteTrans);
  watch("../dev/img/**/*.{gif,png,jpg,jpeg,svg}", copyImages);
  watch("../dev/scss/**/*.scss", scssTask);
  watch("../dev/js/**/*.js", jsTask);
}

exports.default = series(
  copyViews,
  copySiteTrans,
  copyImages,
  scssTask,
  jsTask,
  watchTask
);
