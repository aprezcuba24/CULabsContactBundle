<?php

namespace CULabs\ContactBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MensajeMasivoCRUDControllerTest extends WebTestCase
{
    /*
    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        // Create a new entry in the database
        $crawler = $client->request('GET', '/admin/mensajemasivo/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        // Fill in the form and submit it
        $form = $crawler->selectButton('Save')->form(array(
            'culabs_contactbundle_mensajemasivotype[name]'  => 'Test',
            // ... other fields to fill
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertTrue($crawler->filter('td:contains("Test")')->count() > 0);

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Edit')->link());

        $form = $crawler->selectButton('Save')->form(array(
            'culabs_contactbundle_mensajemasivotype[name]'  => 'Foo',
            // ... other fields to fill
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertTrue($crawler->filter('td:contains("Foo")')->count() > 0);

        // Delete the entity
        $entity = $client->getContainer()->get('doctrine')->getEntityManager()->getRepository('CULabsContactBundle:MensajeMasivo')->findOneByName('Foo');
        $crawler = $client->request('GET', sprintf('/admin/mensajemasivo/%s/delete', $entity->getId()));
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/Foo/', $client->getResponse()->getContent());
    }
    */
}