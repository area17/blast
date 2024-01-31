#!/usr/bin/env node
import fs from 'fs';
import resolveConfig from 'tailwindcss/resolveConfig.js';

try {
  const config = import(process.env.CONFIGPATH);
  const tempDir = './tmp';
  const outputPath = `${tempDir}/tailwind.config.php`;
  const fullConfig = resolveConfig(config);

  function parseConfig(data) {
    let output = '';

    for (const [key, item] of Object.entries(data)) {
      const value = item == null || typeof item === 'function' ? null : item;
      const str =
        value && typeof value === 'object'
          ? parseConfig(value)
          : JSON.stringify(value);

      output += `'${key}' => ${str},`;
    }

    return `[${output}]`;
  }

  try {
    if (!fs.existsSync(tempDir)) {
      fs.mkdirSync(tempDir);
    }

    fs.writeFileSync(outputPath, `<?php return ${parseConfig(fullConfig)};`);
  } catch (err) {
    console.error(err);
  }
} catch (err) {
  console.error(err);
}
