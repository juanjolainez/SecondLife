#To setup ElasticSearch


##First, create the index

```
curl -XPUT 'http://localhost:9200/secondlife/'
```

##Make sure it's been created

```
curl 'localhost:9200/_cat/indices?v'
```

It should appear something like
```
health index      pri rep docs.count docs.deleted store.size pri.store.size 
yellow secondlife   5   1          0            0       495b           495b 
```

That means that the index has been created with 5 shards and 1 replica (default options)

##Map the products

In order to perform geolocated searches, the location must be a geopoint. 
It can be done doing this:

```
curl -XPUT 'http://localhost:9200/secondlife/_mapping/products' -d '{"properties":{"location":{"type":"geopoint"}}}'
```

To make sure it's already well mapped, just type the following:

```
curl -XGET 'http://localhost:9200/secondlife/_mapping/products'
```