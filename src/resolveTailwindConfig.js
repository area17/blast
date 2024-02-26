#!/usr/bin/env node

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

async function resolveTailwindConfig() {
  const fs = await import('fs');
  const { default: resolveConfig } = await import('tailwindcss/resolveConfig.js')
  const { default: config } = await import(process.env.CONFIGPATH)
  const tempDir = './tmp';
  const outputPath = `${tempDir}/tailwind.config.php`;
  const fullConfig = resolveConfig(config);

  try {
    if (!fs.existsSync(tempDir)) {
      fs.mkdirSync(tempDir);
    }

    fs.writeFileSync(outputPath, `<?php return ${await parseConfig(fullConfig)};`);
  } catch (err) {
    console.error(err);
  }
}

try {
  resolveTailwindConfig()
} catch (err) {
  console.error(err);
}
