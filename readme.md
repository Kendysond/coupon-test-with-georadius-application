# Coupon Generation API with georadius application


### Installation

If you use `git clone git@github.com:Kendysond/coupon-test-with-georadius-application.git`

- `composer install`
- `chmod -R 777 storage bootstrap/cache`
- `cp .env.example .env`
- `php artisan key:generate`

Ensure you configure your DB, then

- `php artisan migrate`


### Tasks to be done

- [x] Generation of new promo codes for events
- [x] The promo code is worth a specific amount of ride
- [x] The promo code can expire
- [x] Can be deactivated
- [x] Return active promo codes
- [x] Return all promo codes
- [x] Only valid when user’s pickup or destination is within x radius of the event venue
- [x] The promo code radius should be configurable 
- [x] To test the validity of the promo code, expose an endpoint that accept origin, destination, the promo code. The api should return the promo code details and a polyline using the destination and origin if promo code is valid and an error otherwise.