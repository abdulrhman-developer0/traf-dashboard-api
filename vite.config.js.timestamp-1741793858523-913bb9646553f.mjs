// vite.config.js
import vue from "file:///F:/abd/projects/github/private/smart/traf-dasboard-ap/node_modules/@vitejs/plugin-vue/dist/index.mjs";
import vueJsx from "file:///F:/abd/projects/github/private/smart/traf-dasboard-ap/node_modules/@vitejs/plugin-vue-jsx/dist/index.mjs";
import laravel from "file:///F:/abd/projects/github/private/smart/traf-dasboard-ap/node_modules/laravel-vite-plugin/dist/index.js";
import { fileURLToPath } from "node:url";
import AutoImport from "file:///F:/abd/projects/github/private/smart/traf-dasboard-ap/node_modules/unplugin-auto-import/dist/vite.js";
import Components from "file:///F:/abd/projects/github/private/smart/traf-dasboard-ap/node_modules/unplugin-vue-components/dist/vite.js";
import { VueRouterAutoImports, getPascalCaseRouteName } from "file:///F:/abd/projects/github/private/smart/traf-dasboard-ap/node_modules/unplugin-vue-router/dist/index.mjs";
import VueRouter from "file:///F:/abd/projects/github/private/smart/traf-dasboard-ap/node_modules/unplugin-vue-router/dist/vite.mjs";
import { defineConfig } from "file:///F:/abd/projects/github/private/smart/traf-dasboard-ap/node_modules/vite/dist/node/index.js";
import Layouts from "file:///F:/abd/projects/github/private/smart/traf-dasboard-ap/node_modules/vite-plugin-vue-layouts/dist/index.mjs";
import vuetify from "file:///F:/abd/projects/github/private/smart/traf-dasboard-ap/node_modules/vite-plugin-vuetify/dist/index.mjs";
import svgLoader from "file:///F:/abd/projects/github/private/smart/traf-dasboard-ap/node_modules/vite-svg-loader/index.js";
import ar from "file:///F:/abd/projects/github/private/smart/traf-dasboard-ap/node_modules/vuetify/lib/locale/ar.mjs";
var __vite_injected_original_import_meta_url = "file:///F:/abd/projects/github/private/smart/traf-dasboard-ap/vite.config.js";
var vite_config_default = defineConfig({
  plugins: [
    // Docs: https://github.com/posva/unplugin-vue-router
    // ℹ️ This plugin should be placed before vue plugin
    VueRouter({
      getRouteName: (routeNode) => {
        return getPascalCaseRouteName(routeNode).replace(/([a-z\d])([A-Z])/g, "$1-$2").toLowerCase();
      },
      routesFolder: "resources/js/pages"
    }),
    vue({
      template: {
        compilerOptions: {
          isCustomElement: (tag) => tag === "swiper-container" || tag === "swiper-slide"
        },
        transformAssetUrls: {
          base: null,
          includeAbsolute: false
        }
      }
    }),
    laravel({
      input: ["resources/js/app.js"],
      refresh: true
    }),
    vueJsx(),
    // Docs: https://github.com/vuetifyjs/vuetify-loader/tree/master/packages/vite-plugin
    vuetify({
      styles: {
        configFile: "resources/styles/variables/_vuetify.scss"
      },
      autoImport: true,
      lang: {
        locales: { ar },
        current: "ar"
      }
    }),
    // Docs: https://github.com/johncampionjr/vite-plugin-vue-layouts#vite-plugin-vue-layouts
    Layouts({
      layoutsDirs: "./resources/js/layouts/"
    }),
    // Docs: https://github.com/antfu/unplugin-vue-components#unplugin-vue-components
    Components({
      dirs: ["resources/js/@core/components", "resources/js/views/demos", "resources/js/components"],
      dts: true,
      resolvers: [
        (componentName) => {
          if (componentName === "VueApexCharts")
            return { name: "default", from: "vue3-apexcharts", as: "VueApexCharts" };
        }
      ]
    }),
    // Docs: https://github.com/antfu/unplugin-auto-import#unplugin-auto-import
    AutoImport({
      imports: ["vue", VueRouterAutoImports, "@vueuse/core", "@vueuse/math", "vue-i18n", "pinia"],
      dirs: [
        "./resources/js/@core/utils",
        "./resources/js/@core/composable/",
        "./resources/js/composables/",
        "./resources/js/utils/",
        "./resources/js/plugins/*/composables/*"
      ],
      vueTemplate: true,
      // ℹ️ Disabled to avoid confusion & accidental usage
      ignore: ["useCookies", "useStorage"],
      eslintrc: {
        enabled: false,
        filepath: "./.eslintrc-auto-import.json"
      }
    }),
    svgLoader()
  ],
  define: { "process.env": {} },
  resolve: {
    alias: {
      "@core-scss": fileURLToPath(new URL("./resources/styles/@core", __vite_injected_original_import_meta_url)),
      "@": fileURLToPath(new URL("./resources/js", __vite_injected_original_import_meta_url)),
      "@themeConfig": fileURLToPath(new URL("./themeConfig.js", __vite_injected_original_import_meta_url)),
      "@core": fileURLToPath(new URL("./resources/js/@core", __vite_injected_original_import_meta_url)),
      "@layouts": fileURLToPath(new URL("./resources/js/@layouts", __vite_injected_original_import_meta_url)),
      "@images": fileURLToPath(new URL("./resources/images/", __vite_injected_original_import_meta_url)),
      "@styles": fileURLToPath(new URL("./resources/styles/", __vite_injected_original_import_meta_url)),
      "@configured-variables": fileURLToPath(new URL("./resources/styles/variables/_template.scss", __vite_injected_original_import_meta_url)),
      "@db": fileURLToPath(new URL("./resources/js/plugins/fake-api/handlers/", __vite_injected_original_import_meta_url)),
      "@api-utils": fileURLToPath(new URL("./resources/js/plugins/fake-api/utils/", __vite_injected_original_import_meta_url))
    }
  },
  build: {
    chunkSizeWarningLimit: 5e3
  },
  optimizeDeps: {
    exclude: ["vuetify"],
    entries: [
      "./resources/js/**/*.vue"
    ]
  }
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCJGOlxcXFxhYmRcXFxccHJvamVjdHNcXFxcZ2l0aHViXFxcXHByaXZhdGVcXFxcc21hcnRcXFxcdHJhZi1kYXNib2FyZC1hcFwiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9maWxlbmFtZSA9IFwiRjpcXFxcYWJkXFxcXHByb2plY3RzXFxcXGdpdGh1YlxcXFxwcml2YXRlXFxcXHNtYXJ0XFxcXHRyYWYtZGFzYm9hcmQtYXBcXFxcdml0ZS5jb25maWcuanNcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfaW1wb3J0X21ldGFfdXJsID0gXCJmaWxlOi8vL0Y6L2FiZC9wcm9qZWN0cy9naXRodWIvcHJpdmF0ZS9zbWFydC90cmFmLWRhc2JvYXJkLWFwL3ZpdGUuY29uZmlnLmpzXCI7aW1wb3J0IHZ1ZSBmcm9tICdAdml0ZWpzL3BsdWdpbi12dWUnXHJcbmltcG9ydCB2dWVKc3ggZnJvbSAnQHZpdGVqcy9wbHVnaW4tdnVlLWpzeCdcclxuaW1wb3J0IGxhcmF2ZWwgZnJvbSAnbGFyYXZlbC12aXRlLXBsdWdpbidcclxuaW1wb3J0IHsgZmlsZVVSTFRvUGF0aCB9IGZyb20gJ25vZGU6dXJsJ1xyXG5pbXBvcnQgQXV0b0ltcG9ydCBmcm9tICd1bnBsdWdpbi1hdXRvLWltcG9ydC92aXRlJ1xyXG5pbXBvcnQgQ29tcG9uZW50cyBmcm9tICd1bnBsdWdpbi12dWUtY29tcG9uZW50cy92aXRlJ1xyXG5pbXBvcnQgeyBWdWVSb3V0ZXJBdXRvSW1wb3J0cywgZ2V0UGFzY2FsQ2FzZVJvdXRlTmFtZSB9IGZyb20gJ3VucGx1Z2luLXZ1ZS1yb3V0ZXInXHJcbmltcG9ydCBWdWVSb3V0ZXIgZnJvbSAndW5wbHVnaW4tdnVlLXJvdXRlci92aXRlJ1xyXG5pbXBvcnQgeyBkZWZpbmVDb25maWcgfSBmcm9tICd2aXRlJ1xyXG5pbXBvcnQgTGF5b3V0cyBmcm9tICd2aXRlLXBsdWdpbi12dWUtbGF5b3V0cydcclxuaW1wb3J0IHZ1ZXRpZnkgZnJvbSAndml0ZS1wbHVnaW4tdnVldGlmeSdcclxuaW1wb3J0IHN2Z0xvYWRlciBmcm9tICd2aXRlLXN2Zy1sb2FkZXInXHJcbmltcG9ydCBhciBmcm9tICd2dWV0aWZ5L2xpYi9sb2NhbGUvYXInXHJcblxyXG4vLyBodHRwczovL3ZpdGVqcy5kZXYvY29uZmlnL1xyXG5leHBvcnQgZGVmYXVsdCBkZWZpbmVDb25maWcoe1xyXG4gIHBsdWdpbnM6IFsvLyBEb2NzOiBodHRwczovL2dpdGh1Yi5jb20vcG9zdmEvdW5wbHVnaW4tdnVlLXJvdXRlclxyXG4gIC8vIFx1MjEzOVx1RkUwRiBUaGlzIHBsdWdpbiBzaG91bGQgYmUgcGxhY2VkIGJlZm9yZSB2dWUgcGx1Z2luXHJcbiAgICBWdWVSb3V0ZXIoe1xyXG4gICAgICBnZXRSb3V0ZU5hbWU6IHJvdXRlTm9kZSA9PiB7XHJcbiAgICAgIC8vIENvbnZlcnQgcGFzY2FsIGNhc2UgdG8ga2ViYWIgY2FzZVxyXG4gICAgICAgIHJldHVybiBnZXRQYXNjYWxDYXNlUm91dGVOYW1lKHJvdXRlTm9kZSlcclxuICAgICAgICAgIC5yZXBsYWNlKC8oW2EtelxcZF0pKFtBLVpdKS9nLCAnJDEtJDInKVxyXG4gICAgICAgICAgLnRvTG93ZXJDYXNlKClcclxuICAgICAgfSxcclxuXHJcbiAgICAgIHJvdXRlc0ZvbGRlcjogJ3Jlc291cmNlcy9qcy9wYWdlcycsXHJcbiAgICB9KSxcclxuICAgIHZ1ZSh7XHJcbiAgICAgIHRlbXBsYXRlOiB7XHJcbiAgICAgICAgY29tcGlsZXJPcHRpb25zOiB7XHJcbiAgICAgICAgICBpc0N1c3RvbUVsZW1lbnQ6IHRhZyA9PiB0YWcgPT09ICdzd2lwZXItY29udGFpbmVyJyB8fCB0YWcgPT09ICdzd2lwZXItc2xpZGUnLFxyXG4gICAgICAgIH0sXHJcblxyXG4gICAgICAgIHRyYW5zZm9ybUFzc2V0VXJsczoge1xyXG4gICAgICAgICAgYmFzZTogbnVsbCxcclxuICAgICAgICAgIGluY2x1ZGVBYnNvbHV0ZTogZmFsc2UsXHJcbiAgICAgICAgfSxcclxuICAgICAgfSxcclxuICAgIH0pLFxyXG4gICAgbGFyYXZlbCh7XHJcbiAgICAgIGlucHV0OiBbJ3Jlc291cmNlcy9qcy9hcHAuanMnXSxcclxuICAgICAgcmVmcmVzaDogdHJ1ZSxcclxuICAgIH0pLFxyXG4gICAgdnVlSnN4KCksIC8vIERvY3M6IGh0dHBzOi8vZ2l0aHViLmNvbS92dWV0aWZ5anMvdnVldGlmeS1sb2FkZXIvdHJlZS9tYXN0ZXIvcGFja2FnZXMvdml0ZS1wbHVnaW5cclxuICAgIHZ1ZXRpZnkoe1xyXG4gICAgICBzdHlsZXM6IHtcclxuICAgICAgICBjb25maWdGaWxlOiAncmVzb3VyY2VzL3N0eWxlcy92YXJpYWJsZXMvX3Z1ZXRpZnkuc2NzcycsXHJcbiAgICAgIH0sXHJcbiAgICAgIGF1dG9JbXBvcnQ6IHRydWUsXHJcbiAgICAgIGxhbmc6IHtcclxuICAgICAgICBsb2NhbGVzOiB7IGFyIH0sXHJcbiAgICAgICAgY3VycmVudDogJ2FyJyxcclxuICAgICAgfSxcclxuICAgIH0pLCAvLyBEb2NzOiBodHRwczovL2dpdGh1Yi5jb20vam9obmNhbXBpb25qci92aXRlLXBsdWdpbi12dWUtbGF5b3V0cyN2aXRlLXBsdWdpbi12dWUtbGF5b3V0c1xyXG4gICAgTGF5b3V0cyh7XHJcbiAgICAgIGxheW91dHNEaXJzOiAnLi9yZXNvdXJjZXMvanMvbGF5b3V0cy8nLFxyXG4gICAgfSksIC8vIERvY3M6IGh0dHBzOi8vZ2l0aHViLmNvbS9hbnRmdS91bnBsdWdpbi12dWUtY29tcG9uZW50cyN1bnBsdWdpbi12dWUtY29tcG9uZW50c1xyXG4gICAgQ29tcG9uZW50cyh7XHJcbiAgICAgIGRpcnM6IFsncmVzb3VyY2VzL2pzL0Bjb3JlL2NvbXBvbmVudHMnLCAncmVzb3VyY2VzL2pzL3ZpZXdzL2RlbW9zJywgJ3Jlc291cmNlcy9qcy9jb21wb25lbnRzJ10sXHJcbiAgICAgIGR0czogdHJ1ZSxcclxuICAgICAgcmVzb2x2ZXJzOiBbXHJcbiAgICAgICAgY29tcG9uZW50TmFtZSA9PiB7XHJcbiAgICAgICAgLy8gQXV0byBpbXBvcnQgYFZ1ZUFwZXhDaGFydHNgXHJcbiAgICAgICAgICBpZiAoY29tcG9uZW50TmFtZSA9PT0gJ1Z1ZUFwZXhDaGFydHMnKVxyXG4gICAgICAgICAgICByZXR1cm4geyBuYW1lOiAnZGVmYXVsdCcsIGZyb206ICd2dWUzLWFwZXhjaGFydHMnLCBhczogJ1Z1ZUFwZXhDaGFydHMnIH1cclxuICAgICAgICB9LFxyXG4gICAgICBdLFxyXG4gICAgfSksIC8vIERvY3M6IGh0dHBzOi8vZ2l0aHViLmNvbS9hbnRmdS91bnBsdWdpbi1hdXRvLWltcG9ydCN1bnBsdWdpbi1hdXRvLWltcG9ydFxyXG4gICAgQXV0b0ltcG9ydCh7XHJcbiAgICAgIGltcG9ydHM6IFsndnVlJywgVnVlUm91dGVyQXV0b0ltcG9ydHMsICdAdnVldXNlL2NvcmUnLCAnQHZ1ZXVzZS9tYXRoJywgJ3Z1ZS1pMThuJywgJ3BpbmlhJ10sXHJcbiAgICAgIGRpcnM6IFtcclxuICAgICAgICAnLi9yZXNvdXJjZXMvanMvQGNvcmUvdXRpbHMnLFxyXG4gICAgICAgICcuL3Jlc291cmNlcy9qcy9AY29yZS9jb21wb3NhYmxlLycsXHJcbiAgICAgICAgJy4vcmVzb3VyY2VzL2pzL2NvbXBvc2FibGVzLycsXHJcbiAgICAgICAgJy4vcmVzb3VyY2VzL2pzL3V0aWxzLycsXHJcbiAgICAgICAgJy4vcmVzb3VyY2VzL2pzL3BsdWdpbnMvKi9jb21wb3NhYmxlcy8qJyxcclxuICAgICAgXSxcclxuICAgICAgdnVlVGVtcGxhdGU6IHRydWUsXHJcblxyXG4gICAgICAvLyBcdTIxMzlcdUZFMEYgRGlzYWJsZWQgdG8gYXZvaWQgY29uZnVzaW9uICYgYWNjaWRlbnRhbCB1c2FnZVxyXG4gICAgICBpZ25vcmU6IFsndXNlQ29va2llcycsICd1c2VTdG9yYWdlJ10sXHJcbiAgICAgIGVzbGludHJjOiB7XHJcbiAgICAgICAgZW5hYmxlZDogZmFsc2UsXHJcbiAgICAgICAgZmlsZXBhdGg6ICcuLy5lc2xpbnRyYy1hdXRvLWltcG9ydC5qc29uJyxcclxuICAgICAgfSxcclxuICAgIH0pLFxyXG4gICAgc3ZnTG9hZGVyKCksXHJcbiAgXSxcclxuICBkZWZpbmU6IHsgJ3Byb2Nlc3MuZW52Jzoge30gfSxcclxuICByZXNvbHZlOiB7XHJcbiAgICBhbGlhczoge1xyXG4gICAgICAnQGNvcmUtc2Nzcyc6IGZpbGVVUkxUb1BhdGgobmV3IFVSTCgnLi9yZXNvdXJjZXMvc3R5bGVzL0Bjb3JlJywgaW1wb3J0Lm1ldGEudXJsKSksXHJcbiAgICAgICdAJzogZmlsZVVSTFRvUGF0aChuZXcgVVJMKCcuL3Jlc291cmNlcy9qcycsIGltcG9ydC5tZXRhLnVybCkpLFxyXG4gICAgICAnQHRoZW1lQ29uZmlnJzogZmlsZVVSTFRvUGF0aChuZXcgVVJMKCcuL3RoZW1lQ29uZmlnLmpzJywgaW1wb3J0Lm1ldGEudXJsKSksXHJcbiAgICAgICdAY29yZSc6IGZpbGVVUkxUb1BhdGgobmV3IFVSTCgnLi9yZXNvdXJjZXMvanMvQGNvcmUnLCBpbXBvcnQubWV0YS51cmwpKSxcclxuICAgICAgJ0BsYXlvdXRzJzogZmlsZVVSTFRvUGF0aChuZXcgVVJMKCcuL3Jlc291cmNlcy9qcy9AbGF5b3V0cycsIGltcG9ydC5tZXRhLnVybCkpLFxyXG4gICAgICAnQGltYWdlcyc6IGZpbGVVUkxUb1BhdGgobmV3IFVSTCgnLi9yZXNvdXJjZXMvaW1hZ2VzLycsIGltcG9ydC5tZXRhLnVybCkpLFxyXG4gICAgICAnQHN0eWxlcyc6IGZpbGVVUkxUb1BhdGgobmV3IFVSTCgnLi9yZXNvdXJjZXMvc3R5bGVzLycsIGltcG9ydC5tZXRhLnVybCkpLFxyXG4gICAgICAnQGNvbmZpZ3VyZWQtdmFyaWFibGVzJzogZmlsZVVSTFRvUGF0aChuZXcgVVJMKCcuL3Jlc291cmNlcy9zdHlsZXMvdmFyaWFibGVzL190ZW1wbGF0ZS5zY3NzJywgaW1wb3J0Lm1ldGEudXJsKSksXHJcbiAgICAgICdAZGInOiBmaWxlVVJMVG9QYXRoKG5ldyBVUkwoJy4vcmVzb3VyY2VzL2pzL3BsdWdpbnMvZmFrZS1hcGkvaGFuZGxlcnMvJywgaW1wb3J0Lm1ldGEudXJsKSksXHJcbiAgICAgICdAYXBpLXV0aWxzJzogZmlsZVVSTFRvUGF0aChuZXcgVVJMKCcuL3Jlc291cmNlcy9qcy9wbHVnaW5zL2Zha2UtYXBpL3V0aWxzLycsIGltcG9ydC5tZXRhLnVybCkpLFxyXG4gICAgfSxcclxuICB9LFxyXG4gIGJ1aWxkOiB7XHJcbiAgICBjaHVua1NpemVXYXJuaW5nTGltaXQ6IDUwMDAsXHJcbiAgfSxcclxuICBvcHRpbWl6ZURlcHM6IHtcclxuICAgIGV4Y2x1ZGU6IFsndnVldGlmeSddLFxyXG4gICAgZW50cmllczogW1xyXG4gICAgICAnLi9yZXNvdXJjZXMvanMvKiovKi52dWUnLFxyXG4gICAgXSxcclxuICB9LFxyXG59KVxyXG4iXSwKICAibWFwcGluZ3MiOiAiO0FBQStWLE9BQU8sU0FBUztBQUMvVyxPQUFPLFlBQVk7QUFDbkIsT0FBTyxhQUFhO0FBQ3BCLFNBQVMscUJBQXFCO0FBQzlCLE9BQU8sZ0JBQWdCO0FBQ3ZCLE9BQU8sZ0JBQWdCO0FBQ3ZCLFNBQVMsc0JBQXNCLDhCQUE4QjtBQUM3RCxPQUFPLGVBQWU7QUFDdEIsU0FBUyxvQkFBb0I7QUFDN0IsT0FBTyxhQUFhO0FBQ3BCLE9BQU8sYUFBYTtBQUNwQixPQUFPLGVBQWU7QUFDdEIsT0FBTyxRQUFRO0FBWmdOLElBQU0sMkNBQTJDO0FBZWhSLElBQU8sc0JBQVEsYUFBYTtBQUFBLEVBQzFCLFNBQVM7QUFBQTtBQUFBO0FBQUEsSUFFUCxVQUFVO0FBQUEsTUFDUixjQUFjLGVBQWE7QUFFekIsZUFBTyx1QkFBdUIsU0FBUyxFQUNwQyxRQUFRLHFCQUFxQixPQUFPLEVBQ3BDLFlBQVk7QUFBQSxNQUNqQjtBQUFBLE1BRUEsY0FBYztBQUFBLElBQ2hCLENB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
