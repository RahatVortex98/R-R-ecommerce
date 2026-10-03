import './bootstrap';
import Alpine from 'alpinejs';
window.Alpine = Alpine;

Alpine.store('cart', {
  open: false, count: 0, html: '',
  async load() { const r = await fetch('/cart/drawer', {headers:{Accept:'application/json'}}); this.apply(await r.json()); },
  apply(d) { this.html = d.html; this.count = d.count; },
  async post(url, body) {
    const r = await fetch(url, {method:'POST', headers:{'Content-Type':'application/json',Accept:'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content}, body: JSON.stringify(body)});
    this.apply(await r.json());
  },
  async add(productId, variantId = 0, qty = 1) { await this.post('/cart/add', {product_id:productId, variant_id:variantId, qty}); this.open = true; },
  update(key, qty) { return this.post('/cart/update', {key, qty}); },
  remove(key) { return this.post('/cart/remove', {key}); },
});
Alpine.start();