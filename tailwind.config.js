module.exports = {
  prefix: 'blast-',
  content: ['./resources/**/*.blade.php', './src/Components/**/*.php'],
  corePlugins: {
    preflight: false
  },
  theme: {
    container: {
      center: true,
      padding: {
        DEFAULT: '1rem',
        sm: '2rem',
        lg: '4rem',
        xl: '5rem',
        '2xl': '6rem'
      }
    },
    extend: {
      minWidth: {
        150: '37.5rem'
      }
    }
  },
  variants: {
    extend: {
      width: ['group-hover']
    }
  },
  plugins: []
};
