// vite.config.js
import { defineConfig } from "file:///D:/xampp/htdocs/Bot/SaleBot/node_modules/vite/dist/node/index.js";
import laravel from "file:///D:/xampp/htdocs/Bot/SaleBot/node_modules/laravel-vite-plugin/dist/index.js";
import vue from "file:///D:/xampp/htdocs/Bot/SaleBot/node_modules/@vitejs/plugin-vue/dist/index.mjs";
import svgLoader from "file:///D:/xampp/htdocs/Bot/SaleBot/node_modules/vite-svg-loader/index.js";
var vite_config_default = defineConfig({
  plugins: [
    vue(),
    svgLoader(),
    laravel({
      input: ["resources/js/app.js"],
      buildDirectory: "client/js/build"
      // refresh: true,
    })
  ]
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCJEOlxcXFx4YW1wcFxcXFxodGRvY3NcXFxcQm90XFxcXFNhbGVCb3RcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfZmlsZW5hbWUgPSBcIkQ6XFxcXHhhbXBwXFxcXGh0ZG9jc1xcXFxCb3RcXFxcU2FsZUJvdFxcXFx2aXRlLmNvbmZpZy5qc1wiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9pbXBvcnRfbWV0YV91cmwgPSBcImZpbGU6Ly8vRDoveGFtcHAvaHRkb2NzL0JvdC9TYWxlQm90L3ZpdGUuY29uZmlnLmpzXCI7aW1wb3J0IHsgZGVmaW5lQ29uZmlnIH0gZnJvbSAndml0ZSc7XHJcbmltcG9ydCBsYXJhdmVsIGZyb20gJ2xhcmF2ZWwtdml0ZS1wbHVnaW4nO1xyXG5pbXBvcnQgdnVlIGZyb20gXCJAdml0ZWpzL3BsdWdpbi12dWVcIjtcclxuaW1wb3J0IHN2Z0xvYWRlciBmcm9tIFwidml0ZS1zdmctbG9hZGVyXCI7XHJcblxyXG5leHBvcnQgZGVmYXVsdCBkZWZpbmVDb25maWcoe1xyXG4gICAgcGx1Z2luczogW1xyXG4gICAgICAgIHZ1ZSgpLHN2Z0xvYWRlcigpLFxyXG4gICAgICAgIGxhcmF2ZWwoe1xyXG4gICAgICAgICAgICBpbnB1dDogWydyZXNvdXJjZXMvanMvYXBwLmpzJ10sXHJcbiAgICAgICAgICAgIGJ1aWxkRGlyZWN0b3J5OiAnY2xpZW50L2pzL2J1aWxkJyxcclxuICAgICAgICAgICAgLy8gcmVmcmVzaDogdHJ1ZSxcclxuICAgICAgICB9KSxcclxuICAgIF0sXHJcbn0pO1xyXG4iXSwKICAibWFwcGluZ3MiOiAiO0FBQTZRLFNBQVMsb0JBQW9CO0FBQzFTLE9BQU8sYUFBYTtBQUNwQixPQUFPLFNBQVM7QUFDaEIsT0FBTyxlQUFlO0FBRXRCLElBQU8sc0JBQVEsYUFBYTtBQUFBLEVBQ3hCLFNBQVM7QUFBQSxJQUNMLElBQUk7QUFBQSxJQUFFLFVBQVU7QUFBQSxJQUNoQixRQUFRO0FBQUEsTUFDSixPQUFPLENBQUMscUJBQXFCO0FBQUEsTUFDN0IsZ0JBQWdCO0FBQUE7QUFBQSxJQUVwQixDQUFDO0FBQUEsRUFDTDtBQUNKLENBQUM7IiwKICAibmFtZXMiOiBbXQp9Cg==
