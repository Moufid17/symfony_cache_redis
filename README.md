### Description:

### Collaborateurs: 
- [Mustakim KHAN](https://github.com/Mustakim-Khan)
- [Sonny LUSCAP](https://github.com/Sonny00)
- [GACEM TORKIA HALA](https://github.com/TORKIAHALA)
- [MOUTAROU MOUHAMMED MOUFID AFOLABI](https://github.com/Moufid17/)
---
### Run
1. Cloner le repository : `https://github.com/Moufid17/symfony_cache_redis.git`
2. Installer les dépendences: 
    ```
    cd symfony_cache_redis
    docker-compose build --pull --no-cache
    docker-compose up -d
    ```
### Testing : 
> root : `localhost/`
1. Panier du produit : `localhost/cart`
2. cliquez sur __Acheter__.
3. Page de paiement stripe :
    - Success : 
        - Card number : `4242424242424242`
        - Date d'expiration : `08/25`
        - CVC : `024`
        - Name on card : `customer.one`
    
    - Echec : 
        - Card number : `4000000000003220`
        - Date d'expiration : `08/25`
        - CVC : `024`
        - Name on card : `customer.one`

    > Cliquer sur le button _`Pay`_ 
4. Consulter le redis : 
    - Lancer redis sur le terminal : 
        ```
        docker exec -it container_name redis-cli
        ```
    - Liste des caches items créer :
        ```
        127.0.0.1:6379> keys *
        ```
