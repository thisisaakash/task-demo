import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule, Routes } from '@angular/router';

import { AddManufacturerComponent } from './add-manufacturer/add-manufacturer.component';
import { AddModelsComponent } from './add-models/add-models.component';
import { ViewInventoryComponent } from './view-inventory/view-inventory.component';

const routes: Routes = [
  { path: 'addmanufacturer', component: AddManufacturerComponent },
  { path: 'addmodels', component: AddModelsComponent },
  { path: 'viewinventory', component: ViewInventoryComponent }
];

@NgModule({
  imports: [
    RouterModule.forRoot(routes)
  ],
  exports: [ RouterModule ],
  declarations: []
})
export class AppRoutingModule { }
