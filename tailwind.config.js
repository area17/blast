module.exports = {
  prefix: 'blast-',
  darkMode: false,
  purge: ['./resources/**/*.blade.php'],
  theme: {
    corePlugins: {
      preflight: false
    },
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
