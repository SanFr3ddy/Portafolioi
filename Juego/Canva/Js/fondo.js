const c = document.querySelector('#c');
const ctx = c.getContext('2d');

c.style.width = (c.width = window.innerWidth);
c.style.height = (c.height = window.innerHeight);

const TAU = Math.PI + Math.PI;

const colors = [
    TAU - TAU,
    TAU * TAU,
    TAU * TAU * TAU * TAU,
  ]

const animate = (time) => {
  requestAnimationFrame(animate);
  
  const TTTTAU = TAU * TAU * TAU * TAU;
  
  for (let y = TAU - TAU; y < c.height; y+=TAU) {
    for (let x = TAU - TAU; x < c.width; x+=TAU) {
      const patternEnthropy = Math.floor(
        time / TTTTAU
        + ((x / TTTTAU) + (Math.sin(y / TAU) / TAU) - (Math.cos(y * TTTTAU)))
        + ((y / TTTTAU) + (Math.cos(x / TAU) / TAU))
      );
      
      const index = patternEnthropy % colors.length;
      
      ctx.fillStyle = `
        hsl(
          ${colors[index]}deg
          ${TAU + index * TAU * TAU}%
          ${TAU + index * TAU * TAU}%
        )`;
      ctx.fillRect(x, y, TAU, TAU);
      
    }
  }
  
  colors.forEach((c,i) => colors[i]+=TAU)
}

animate(TAU - TAU);