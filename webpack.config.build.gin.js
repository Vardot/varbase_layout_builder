const path = require('path');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const SVGSpritemapPlugin = require('svg-spritemap-webpack-plugin');
const autoprefixer = require('autoprefixer');
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts');

const isDev = (process.env.NODE_ENV !== 'production');

// Compiling SCSS files for Gin.
module.exports = {
  mode: 'production',
  entry: {
    // ################################################
    // SCSS
    // ################################################
    // Base
    'base/gin': ['./themes/gin/scss/base/gin.scss'],
    // Components
    'components/ajax': ['./themes/gin/scss/components/ajax.scss'],
    'components/edit_form': ['./themes/gin/scss/components/edit_form.scss'],
    'components/linkit': ['./themes/gin/scss/components/linkit.scss'],
    'components/paragraphs': ['./themes/gin/scss/components/paragraphs.scss'],
    'components/sidebar': ['./themes/gin/scss/components/sidebar.scss'],
    'components/webform': ['./themes/gin/scss/components/webform.scss'],
    'components/autocomplete': ['./themes/gin/scss/components/autocomplete.scss'],
    'components/contextual_links': ['./themes/gin/scss/components/contextual_links.scss'],
    'components/entity_browser': ['./themes/gin/scss/components/entity_browser.scss'],
    'components/media_library': ['./themes/gin/scss/components/media_library.scss'],
    'components/responsive_preview': ['./themes/gin/scss/components/responsive_preview.scss'],
    'components/tabs': ['./themes/gin/scss/components/tabs.scss'],
    'components/workbench': ['./themes/gin/scss/components/workbench.scss'],
    'components/autosave': ['./themes/gin/scss/components/autosave.scss'],
    'components/description_toggle': ['./themes/gin/scss/components/description_toggle.scss'],
    'components/entity_reference_layout': ['./themes/gin/scss/components/entity_reference_layout.scss'],
    'components/module_filter': ['./themes/gin/scss/components/module_filter.scss'],
    'components/revisions': ['./themes/gin/scss/components/revisions.scss'],
    'components/toolbar': ['./themes/gin/scss/components/toolbar.scss'],
    'components/breadcrumb': ['./themes/gin/scss/components/breadcrumb.scss'],
    'components/dialog': ['./themes/gin/scss/components/dialog.scss'],
    'components/inline_entity_form': ['./themes/gin/scss/components/inline_entity_form.scss'],
    'components/node_preview': ['./themes/gin/scss/components/node_preview.scss'],
    'components/toolbar_secondary': ['./themes/gin/scss/components/toolbar_secondary.scss'],
    'components/chosen': ['./themes/gin/scss/components/chosen.scss'],
    'components/dropzonejs': ['./themes/gin/scss/components/dropzonejs.scss'],
    'components/layout_paragraphs': ['./themes/gin/scss/components/layout_paragraphs.scss'],
    'components/paragraphs_ee': ['./themes/gin/scss/components/paragraphs_ee.scss'],
    // Layout
    'layout/classic_toolbar': ['./themes/gin/scss/layout/classic_toolbar.scss'],
    'layout/horizontal_toolbar': ['./themes/gin/scss/layout/horizontal_toolbar.scss'],
    'layout/toolbar': ['./themes/gin/scss/layout/toolbar.scss'],
    // Theme
    'theme/ckeditor': ['./themes/gin/scss/theme/ckeditor.scss'],
    'theme/dialog': ['./themes/gin/scss/theme/dialog.scss'],
    'theme/vartheme-claro.theme.style': ['./themes/gin/scss/theme/vartheme-claro.theme.style.scss'],
  },
  output: {
    path: path.resolve(__dirname, 'themes/gin/css'),
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
