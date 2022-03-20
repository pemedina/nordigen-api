<?php

namespace Pemedina\Nordigen\Tests;

use Pemedina\Nordigen\Nordigen;
use PHPUnit\Framework\TestCase;

class NordigenTest extends TestCase
{


  public function testCreateRequisition()
  {
    $stub = $this->createStub(Nordigen::class);
    $stub->method('createRequisition')->willReturn('foo');

    $this->assertSame('foo', $stub->createRequisition('foo'));
  }

  public function testGetRequisitions()
  {
    $stub = $this->createStub(Nordigen::class);
    $stub->method('getRequisitions')->willReturn('foo');

    $this->assertSame('foo', $stub->getRequisitions('foo'));
  }

  public function testDeleteRequisition()
  {
    $stub = $this->createStub(Nordigen::class);
    $stub->method('deleteRequisition')->willReturn('foo');

    $this->assertSame('foo', $stub->deleteRequisition('foo'));
  }

  public function testGetInstitutions()
  {
    $stub = $this->createStub(Nordigen::class);
    $stub->method('getInstitutions')->willReturn('foo');

    $this->assertSame('foo', $stub->getInstitutions('foo'));
  }

  public function testDeleteAgreement()
  {
    $stub = $this->createStub(Nordigen::class);
    $stub->method('deleteAgreement')->willReturn('foo');

    $this->assertSame('foo', $stub->deleteAgreement('foo'));
  }


  public function testGetBalances()
  {
    $stub = $this->createStub(Nordigen::class);
    $stub->method('getBalances')->willReturn('foo');

    $this->assertSame('foo', $stub->getBalances('foo'));
  }

  public function testCreateAgreement()
  {
    $stub = $this->createStub(Nordigen::class);
    $stub->method('createAgreement')->willReturn('foo');

    $this->assertSame('foo', $stub->createAgreement('foo'));
  }

  public function testGetTransactions()
  {
    $stub = $this->createStub(Nordigen::class);
    $stub->method('getTransactions')->willReturn('foo');

    $this->assertSame('foo', $stub->getTransactions('foo'));
  }

  public function testGetAccounts()
  {
    $stub = $this->createStub(Nordigen::class);
    $stub->method('getAccounts')->willReturn('foo');

    $this->assertSame('foo', $stub->getAccounts('foo'));
  }

  public function testGetDetails()
  {
    $stub = $this->createStub(Nordigen::class);
    $stub->method('getDetails')->willReturn('foo');

    $this->assertSame('foo', $stub->getDetails('foo'));
  }

  public function testGetAgreements()
  {
    $stub = $this->createStub(Nordigen::class);
    $stub->method('getAgreements')->willReturn('foo');

    $this->assertSame('foo', $stub->getAgreements('foo'));
  }
}
