const path = require('path');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const SVGSpritemapPlugin = require('svg-spritemap-webpack-plugin');
const autoprefixer = require('autoprefixer');
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts');

const isDev = (process.env.NODE_ENV !== 'production');

// Compiling SCSS files for Claro.
module.exports = {
  mode: 'production',
  entry: {
    // ################################################
    // SCSS
    // ################################################
    // Base
    'base/variables': ['./themes/claro10/scss/base/variables.scss'],
    'base/elements': ['./themes/claro10/scss/base/elements.scss'],
    'base/typography': ['./themes/claro10/scss/base/typography.scss'],
    // Components
    'components/accordion': ['./themes/claro10/scss/components/accordion.scss'],
    'components/dialog': ['./themes/claro10/scss/components/dialog.scss'],
    'components/form--select': ['./themes/claro10/scss/components/form--select.scss'],
    'components/progress': ['./themes/claro10/scss/components/progress.scss'],
    'components/tabledrag': ['./themes/claro10/scss/components/tabledrag.scss'],
    'components/action-link': ['./themes/claro10/scss/components/action-link.scss'],
    'components/divider': ['./themes/claro10/scss/components/divider.scss'],
    'components/form--text': ['./themes/claro10/scss/components/form--text.scss'],
    'components/skip-link': ['./themes/claro10/scss/components/skip-link.scss'],
    'components/table--file-multiple-widget': ['./themes/claro10/scss/components/table--file-multiple-widget.scss'],
    'components/ajax-progress-module': ['./themes/claro10/scss/components/ajax-progress-module.scss'],
    'components/dropbutton': ['./themes/claro10/scss/components/dropbutton.scss'],
    'components/help': ['./themes/claro10/scss/components/help.scss'],
    'components/system-admin--admin-list': ['./themes/claro10/scss/components/system-admin--admin-list.scss'],
    'components/tableselect': ['./themes/claro10/scss/components/tableselect.scss'],
    'components/autocomplete-loading-module': ['./themes/claro10/scss/components/autocomplete-loading-module.scss'],
    'components/entity-meta': ['./themes/claro10/scss/components/entity-meta.scss'],
    'components/icon-link': ['./themes/claro10/scss/components/icon-link.scss'],
    'components/system-admin--links': ['./themes/claro10/scss/components/system-admin--links.scss'],
    'components/tablesort-indicator': ['./themes/claro10/scss/components/tablesort-indicator.scss'],
    'components/breadcrumb': ['./themes/claro10/scss/components/breadcrumb.scss'],
    'components/fieldset': ['./themes/claro10/scss/components/fieldset.scss'],
    'components/image-preview': ['./themes/claro10/scss/components/image-preview.scss'],
    'components/system-admin--modules': ['./themes/claro10/scss/components/system-admin--modules.scss'],
    'components/tables': ['./themes/claro10/scss/components/tables.scss'],
    'components/buttons': ['./themes/claro10/scss/components/buttons.scss'],
    'components/file': ['./themes/claro10/scss/components/file.scss'],
    'components/media-library.ui': ['./themes/claro10/scss/components/media-library.ui.scss'],
    'components/system-admin--panel': ['./themes/claro10/scss/components/system-admin--panel.scss'],
    'components/tabs': ['./themes/claro10/scss/components/tabs.scss'],
    'components/card': ['./themes/claro10/scss/components/card.scss'],
    'components/form--checkbox-radio': ['./themes/claro10/scss/components/form--checkbox-radio.scss'],
    'components/media': ['./themes/claro10/scss/components/media.scss'],
    'components/vertical-tabs': ['./themes/claro10/scss/components/vertical-tabs.scss'],
    'components/container-inline-module': ['./themes/claro10/scss/components/container-inline-module.scss'],
    'components/form--field-multiple': ['./themes/claro10/scss/components/form--field-multiple.scss'],
    'components/menus-and-lists': ['./themes/claro10/scss/components/menus-and-lists.scss'],
    'components/system-status-counter': ['./themes/claro10/scss/components/system-status-counter.scss'],
    'components/views-exposed-form': ['./themes/claro10/scss/components/views-exposed-form.scss'],
    'components/container-inline': ['./themes/claro10/scss/components/container-inline.scss'],
    'components/form--managed-file': ['./themes/claro10/scss/components/form--managed-file.scss'],
    'components/messages': ['./themes/claro10/scss/components/messages.scss'],
    'components/views_ui.admin': ['./themes/claro10/scss/components/views_ui.admin.scss'],
    'components/content-header': ['./themes/claro10/scss/components/content-header.scss'],
    'components/form--password-confirm': ['./themes/claro10/scss/components/form--password-confirm.scss'],
    'components/pager': ['./themes/claro10/scss/components/pager.scss'],
    'components/views-ui': ['./themes/claro10/scss/components/views-ui.scss'],
    'components/details': ['./themes/claro10/scss/components/details.scss'],
    'components/form': ['./themes/claro10/scss/components/form.scss'],
    'components/page-title': ['./themes/claro10/scss/components/page-title.scss'],
    // Layout
    'layout/breadcrumb': ['./themes/claro10/scss/layout/breadcrumb.scss'],
    'layout/card-list': ['./themes/claro10/scss/layout/card-list.scss'],
    'layout/layout': ['./themes/claro10/scss/layout/layout.scss'],
    'layout/local-actions': ['./themes/claro10/scss/layout/local-actions.scss'],
    'layout/node-add': ['./themes/claro10/scss/layout/node-add.scss'],
    'layout/system-admin--layout': ['./themes/claro10/scss/layout/system-admin--layout.scss'],
    // Theme
    'theme/bootstrap-styles.dark': ['./themes/claro10/scss/theme/bootstrap-styles.dark.scss'],
    'theme/colors': ['./themes/claro10/scss/theme/colors.scss'],
    'theme/field-ui.admin': ['./themes/claro10/scss/theme/field-ui.admin.scss'],
    'theme/filter.theme': ['./themes/claro10/scss/theme/filter.theme.scss'],
    'theme/js': ['./themes/claro10/scss/theme/js.scss'],
    'theme/media-library': ['./themes/claro10/scss/theme/media-library.scss'],
    'theme/vartheme-claro.theme.style': ['./themes/claro10/scss/theme/vartheme-claro.theme.style.scss'],
  },
  output: {
    path: path.resolve(__dirname, 'themes/claro10/css'),
    pathinfo: true,
    publicPath: '',
  },
  module: {
    rules: [
      {
        test: /\.(png|jpe?g|gif|svg)$/,
        exclude: /sprite\.svg$/,
        type: 'javascript/auto',
        use: [{
            loader: 'file-loader',
            options: {
              name: '[path][name].[ext]', //?[contenthash]
              outputPath: '../../'
            },
          },
          {
            loader: 'img-loader',
            options: {
              enabled: !isDev,
            },
          },
        ],
      },
      {
        test: /\.(css|scss)$/,
        use: [
          {
            loader: MiniCssExtractPlugin.loader,
            options: {
              name: '[name].[ext]?[hash]',
            }
          },
          {
            loader: 'css-loader',
            options: {
              sourceMap: isDev,
              importLoaders: 2,
              url: (url) => {
                // Don't handle sprite svg
                if (url.includes('sprite.svg')) {
                  return false;
                }

                return true;
              },
            },
          },
          {
            loader: 'postcss-loader',
            options: {
              sourceMap: isDev,
              postcssOptions: {
                plugins: [
                  autoprefixer(),
                  ['postcss-perfectionist', {
                    format: 'expanded',
                    indentSize: 2,
                    trimLeadingZero: true,
                    zeroLengthNoUnit: false,
                    maxAtRuleLength: false,
                    maxSelectorLength: false,
                    maxValueLength: false,
                  }]
                ],
              },
            },
          },
          {
            loader: 'sass-loader',
            options: {
              sourceMap: isDev,
              // Global SCSS imports:
              additionalData: `
                @use "sass:color";
                @use "sass:math";
              `,
            },
          },
        ],
      },
    ],
  },
  resolve: {
    modules: [
      path.join(__dirname, 'node_modules'),
    ],
    extensions: ['.js', '.json'],
  },
  plugins: [
    new RemoveEmptyScriptsPlugin(),
    new CleanWebpackPlugin({
      cleanStaleWebpackAssets: false
    }),
    new MiniCssExtractPlugin(),
  ],
  watchOptions: {
    aggregateTimeout: 300,
    ignored: ['**/*.woff', '**/*.json', '**/*.woff2', '**/*.jpg', '**/*.png', '**/*.svg', 'node_modules', 'images'],
  }
};
