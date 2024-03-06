#!/usr/bin/env node
const TEMP_DIR = './tmp';
const OUTPUT_PATH = `${TEMP_DIR}/tailwind.config.php`;

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

async function resolveTailwindConfig() {
  const fs = await import('fs');
  const { default: resolveConfig } = await import(
    'tailwindcss/resolveConfig.js'
  );
  const { default: config } = await import(process.env.CONFIGPATH);

  const fullConfig = resolveConfig(config);

  try {
    if (!fs.existsSync(TEMP_DIR)) {
      fs.mkdirSync(TEMP_DIR);
    }

    fs.writeFileSync(OUTPUT_PATH, `<?php return ${parseConfig(fullConfig)};`);
  } catch (err) {
    console.error(err);
  }
}

try {
  resolveTailwindConfig();
} catch (err) {
  console.error(err);
}
