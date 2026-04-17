import { defineConfig } from "vite";
import path from "path";

export default defineConfig({
  plugins: [],
  build: {
    manifest: true,
    outDir: "assets/dist",
    rollupOptions: {
      input: {
        main: path.resolve(__dirname, "assets/js/app.js"),
        style: path.resolve(__dirname, "assets/scss/main.scss"),
      },
    },
  },
  server: {
    cors: true,
    strictPort: true,
    port: 5173,
    hmr: {
      host: "localhost",
    },
  },
});
