// This file contains utility functions for the e-commerce website.

export function formatPrice(price) {
    return `$${parseFloat(price).toFixed(2)}`;
}

export function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
}

export function calculateDiscountedPrice(price, discount) {
    return price - (price * (discount / 100));
}

export function isEmpty(value) {
    return value === null || value === undefined || value.trim() === '';
}