#!/usr/bin/env node
async function resolve() {
  const fs = await import('fs');
  const resolveConfig = await import('tailwindcss/resolveConfig.js')
  const config = await import(process.env.CONFIGPATH)
  const tempDir = './tmp';
  const outputPath = `${tempDir}/tailwind.config.php`;
  const fullConfig = resolveConfig.default(config.default);

  async function parseConfig(data) {
    let output = '';

    for (const [key, item] of Object.entries(data)) {
      const value = item == null || typeof item === 'function' ? null : item;
      const str =
        value && typeof value === 'object'
          ? await parseConfig(value)
          : JSON.stringify(value);

      output += `'${key}' => ${str},`;
    }

    return `[${output}]`;
  }

  try {
    if (!fs.existsSync(tempDir)) {
      fs.mkdirSync(tempDir);
    }

    fs.writeFileSync(outputPath, `<?php return ${await parseConfig(fullConfig)};`);
  } catch (err) {
    console.error(err);
  }
}

(async () => {
  try {
    await resolve()
  } catch (err) {
    console.error(err);
  }
})()
