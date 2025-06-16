
export const getIntValue = (value) => {
    if (value == '') return 0;
    return !isNaN(value) ? parseInt(value) : 0;
};

export const sumCost = (costYears) => {
    return costYears.reduce((a, b) => getIntValue(a) + getIntValue(b), 0);
};

export const formatNumber = (value, zeroValue = '') => {
    if (value == '') return zeroValue;

    if (value == 0) return zeroValue;

    return !isNaN(value) ? value.toLocaleString() : zeroValue;
}

export const convertToRoman = (num) => {
    const romanNumerals = [
      { value: 1000, symbol: 'M' },
      { value: 900, symbol: 'CM' },
      { value: 500, symbol: 'D' },
      { value: 400, symbol: 'CD' },
      { value: 100, symbol: 'C' },
      { value: 90, symbol: 'XC' },
      { value: 50, symbol: 'L' },
      { value: 40, symbol: 'XL' },
      { value: 10, symbol: 'X' },
      { value: 9, symbol: 'IX' },
      { value: 5, symbol: 'V' },
      { value: 4, symbol: 'IV' },
      { value: 1, symbol: 'I' }
    ];
  
    let result = '';
  
    for (const numeral of romanNumerals) {
      while (num >= numeral.value) {
        result += numeral.symbol;
        num -= numeral.value;
      }
    }
  
    return result;
  }

  export const numberToAlphabet = (number) => {
    if (number < 1 || number > 26) {
      return "Invalid";
    }
    
    return String.fromCharCode(64 + number);
  }